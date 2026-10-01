# -*- coding: utf-8 -*-
"""
Scrapea la tabla "Match History" renderizada por React con Playwright y genera matches.txt:
<fecha>\t<player_home>\t<FT_home>-<FT_away> (<HT_home>-<HT_away>)\t<player_away>
\t<team_home>\t\t<team_away>

Requisitos:
  pip install playwright beautifulsoup4
  python -m playwright install chromium
"""

import re
import asyncio
from pathlib import Path
from typing import List, Tuple
from bs4 import BeautifulSoup
from playwright.async_api import async_playwright

# ================================
# Configuración (edita aquí)
# ================================
H2H_URL   = "https://app.tipmanager.net/en/sports/fifa/h2h/24637x24951"
OUT_TXT   = "matches.txt"
DEBUG     = True
SAVE_TXT  = True         # guarda el texto plano de cada página: pages/page_N.txt
SAVE_HTML = False        # opcional: guarda HTML de cada página: pages/page_N.html

# Si la vista requiere sesión, pega tu cookie COMPLETA aquí (si no, deja ""):
COOKIE = ""  # "__stripe_mid=...; _ga=...; NEXT_LOCALE=en; ...; sb-...=...; __stripe_sid=..."

# Límites de navegación para no quedar en bucles
MAX_PAGES    = 50   # clicks de Next/Older/Load more como máximo
SCROLL_STEPS = 10   # pasos de scroll por página (si es “infinite scroll”)
SCROLL_DELTA = 1400 # píxeles por scroll
WAIT_NET_MS  = 2000 # ms a esperar después de acciones (idle breve)

# Patrones de extracción
SCORE_RE = re.compile(r"^\s*(\d+)\s*-\s*(\d+)\s*\(\s*(\d+)\s*-\s*(\d+)\s*\)\s*$")
DATE_RE  = re.compile(r"\b\d{1,2}/\d{1,2}/\d{4},\s*\d{1,2}:\d{2}:\d{2}\s*(AM|PM)\b", re.I)

# ================================
# Utilidades
# ================================
def parse_cookie_string(cookie_str: str):
    """Convierte 'k1=v1; k2=v2' en lista de dicts para context.add_cookies."""
    jar = []
    for part in [p.strip() for p in cookie_str.split(";") if p.strip()]:
        if "=" not in part:
            continue
        name, value = part.split("=", 1)
        jar.append({
            "name": name.strip(),
            "value": value.strip(),
            "domain": "app.tipmanager.net",
            "path": "/",
            "httpOnly": False,
            "secure": True,
        })
    return jar

def extract_match_history_text(html: str) -> List[str]:
    """
    Devuelve una lista de líneas (tokens) en orden visual del bloque 'Match History'.
    """
    soup = BeautifulSoup(html, "html.parser")
    title = soup.find(string=re.compile(r"\bMatch History\b", re.I))
    container = None
    if title:
        node = title.parent
        for _ in range(8):
            if node and node.find(string=re.compile(r"Score FT\(HT\)", re.I)):
                container = node
                break
            node = node.parent if node else None
        if not container:
            container = title.parent
    else:
        container = soup  # fallback

    # Tomar texto plano del contenedor renderizado
    text = container.get_text("\n", strip=True) if container else soup.get_text("\n", strip=True)
    # Limpieza de labels conocidos
    cleaned = []
    for ln in [l.strip() for l in text.split("\n") if l.strip()]:
        low = ln.lower()
        if low in {"match history", "search", "score ft(ht)"}:
            continue
        cleaned.append(ln)
    return cleaned

def parse_rows_from_tokens(tokens: List[str]) -> List[str]:
    """
    A partir de tokens (líneas), detecta cada marcador FT(HT) y arma filas:
    [fecha, player1, team1, score, player2, team2]
    """
    out = []
    i = 0
    L = len(tokens)
    while i < L:
        m = SCORE_RE.match(tokens[i])
        if not m:
            i += 1
            continue

        if i >= 3 and i + 2 < L:
            date_raw = tokens[i - 3]
            player1  = tokens[i - 2]
            team1    = tokens[i - 1]
            score    = tokens[i]
            player2  = tokens[i + 1]
            team2    = tokens[i + 2]

            if DATE_RE.search(date_raw):
                out.append(f"{date_raw}\t{player1}\t{score}\t{player2}\n\t{team1}\t\t{team2}")
                # Salta dos posiciones extra para evitar solapes (opcional)
                i += 3
                continue
        i += 1
    return out

async def click_next_or_load_more(page) -> bool:
    """
    Intenta avanzar paginación en diferentes variantes.
    Devuelve True si hizo click en algo que cargó más resultados.
    """
    selectors = [
        'a[rel="next"]',
        'button:has-text("Next")',
        'button:has-text("Older")',
        'button:has-text("Load more")',
        'a:has-text("Next")',
        'a:has-text("Older")',
        'a:has-text("Load more")',
    ]
    for sel in selectors:
        loc = page.locator(sel)
        count = await loc.count()
        for idx in range(count):
            btn = loc.nth(idx)
            try:
                if await btn.is_enabled():
                    await btn.click()
                    await page.wait_for_timeout(WAIT_NET_MS)
                    return True
            except:
                continue
    return False

async def do_infinite_scroll(page) -> bool:
    """Hace algunos scrolls. Devuelve True si el contenido aumentó."""
    before = await page.evaluate("document.body.scrollHeight")
    for _ in range(SCROLL_STEPS):
        await page.evaluate(f"window.scrollBy(0, {SCROLL_DELTA});")
        await page.wait_for_timeout(300)
    after = await page.evaluate("document.body.scrollHeight")
    return after > before

# ================================
# Runner principal
# ================================
async def run():
    outdir = Path("pages")
    outdir.mkdir(exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await p.new_context(user_agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36")

        # Si hay cookie, cargarla en el contexto
        if COOKIE.strip():
            try:
                await context.add_cookies(parse_cookie_string(COOKIE))
            except Exception as e:
                print("[WARN] No se pudo cargar COOKIE:", e)

        page = await context.new_page()
        await page.goto(H2H_URL, wait_until="networkidle")

        all_lines: List[str] = []
        seen_hashes = set()
        page_no = 1
        clicks = 0

        while True:
            # Texto renderizado del DOM completo
            html = await page.content()
            soup_text = extract_match_history_text(html)

            if SAVE_TXT:
                (outdir / f"page_{page_no}.txt").write_text("\n".join(soup_text), encoding="utf-8")
            if SAVE_HTML:
                (outdir / f"page_{page_no}.html").write_text(html, encoding="utf-8")

            if DEBUG:
                print(f"[DEBUG] P{page_no}: tokens={len(soup_text)}")

            # Parsear filas en esta “página”
            page_lines = parse_rows_from_tokens(soup_text)
            if DEBUG:
                print(f"[INFO] P{page_no}: partidos detectados={len(page_lines)}")

            # Acumular evitando duplicados
            for L in page_lines:
                if L not in all_lines:
                    all_lines.append(L)

            # Evitar bucles: si el contenido no cambia, cortamos
            h = hash("|".join(soup_text[:200]))  # hash simple del comienzo
            if h in seen_hashes:
                if DEBUG:
                    print("[INFO] Contenido repetido. Deteniendo.")
                break
            seen_hashes.add(h)

            # Intentar avanzar paginación o cargar más
            moved = await click_next_or_load_more(page)
            if moved:
                clicks += 1
                if clicks >= MAX_PAGES:
                    if DEBUG:
                        print("[INFO] Llegó a MAX_PAGES, deteniendo.")
                    break
                page_no += 1
                continue

            # Si no hay botones, intentamos scroll
            grew = await do_infinite_scroll(page)
            await page.wait_for_timeout(400)
            if grew:
                page_no += 1
                # volver a iterar para extraer lo nuevo
                continue

            # Si no creció y no hay botones → no hay más
            break

        await browser.close()

    # Escribir archivo final
    Path(OUT_TXT).write_text("\n".join(all_lines) + ("\n" if all_lines else ""), encoding="utf-8")
    print(f"[OK] Guardado {len(all_lines)} partidos en {OUT_TXT}")
    if not all_lines:
        print("[AYUDA] No se detectó ninguna fila. Revisa pages/page_1.txt para ver qué texto se renderiza.")
        if not COOKIE:
            print("       Esta vista puede requerir sesión. Pega tu COOKIE en el script y vuelve a ejecutar.")

if __name__ == "__main__":
    asyncio.run(run())
