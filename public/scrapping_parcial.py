from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager

# Configuración de Chromium
options = Options()
options.add_argument("--headless")
options.add_argument("user-agent=Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36")
options.binary_location = "/usr/bin/chromium-browser"  # Ruta al binario de Chromium
options.add_argument("--no-sandbox")
options.add_argument("--disable-dev-shm-usage")

# Especificar la ubicación del chromedriver
driver = webdriver.Chrome(ChromeDriverManager(version="126.0.6478.114").install(), options=options)

#driver = webdriver.Chrome(service=service, options=options)

# Abrir la página de Forebet
url = "https://www.forebet.com/es/esoccer/esoccer-battle-8-mins-play"
driver.get(url)

resultados_parciales = []
resultados_totales = []

try:
    # Esperar hasta que el div 'schema' esté presente
    schema_div = WebDriverWait(driver, 20).until(
        EC.visibility_of_element_located((By.CLASS_NAME, "schema"))
    )

    # Obtener todos los divs que representan las filas de la tabla
    filas = schema_div.find_elements(By.XPATH, ".//div[contains(@class, 'rcnt')]")
    # Recorrer los divs de filas
    for fila in filas:
        # Extraer datos de cada celda
        celdas = fila.find_elements(By.TAG_NAME, "div")

        if len(celdas) >= 4:
            players = celdas[3].text
            if celdas[14].text != "FT":
                if celdas[14].text != "":
                    valor = int(celdas[14].text)
                    if 3 < valor < 6:
                        tipo = celdas[15].text
                        resultado = celdas[16].text
                        resultado = f"Players {players} res {resultado} | {tipo}"
                        resultados_parciales.append(resultado)

finally:
    driver.quit()

archivo_txt_parciales = "resultados_parciales.txt"
with open(archivo_txt_parciales, "a") as f:
    for linea in resultados_parciales:
        f.write(linea + "\n")

print(f"Archivo creado con éxito.")
