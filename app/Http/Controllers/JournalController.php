<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(int $year, int $month)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = (clone $start)->endOfMonth();

        // Grilla: Domingo->Sábado
        $gridStart = (clone $start)->startOfWeek(Carbon::SUNDAY);
        $gridEnd   = (clone $end)->endOfWeek(Carbon::SATURDAY);

        $tradesByDay = Trade::whereBetween('trade_date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->get()
            ->groupBy(fn (Trade $t) => $t->trade_date->toDateString());

        // Resumen por día
        $days = [];
        $cursor = $gridStart->copy();
        while ($cursor <= $gridEnd) {
            $dateKey = $cursor->toDateString();
            $dayTrades = $tradesByDay->get($dateKey, collect());
            $summary = $this->summarizeTrades($dayTrades);

            $days[$dateKey] = [
                'date' => $cursor->copy(),
                'in_month' => $cursor->month === $month,
                'summary' => $summary,
                'color_class' => $this->dayColorClass($summary['profit_pct']),
                'badge_class' => $this->dayBadgeClass($summary['profit_pct']),
            ];

            $cursor->addDay();
        }

        // Resumen por semana
        $weeks = [];
        $cursor = $gridStart->copy();
        while ($cursor <= $gridEnd) {
            $weekStart = $cursor->copy();
            $weekEnd = $cursor->copy()->addDays(6);

            $weekTrades = Trade::whereBetween('trade_date', [$weekStart->toDateString(), $weekEnd->toDateString()])->get();
            $weeks[$weekStart->toDateString()] = $this->summarizeTrades($weekTrades);

            $cursor->addWeek();
        }

        // Total mes (solo dentro del mes)
        $monthTrades = Trade::whereBetween('trade_date', [$start->toDateString(), $end->toDateString()])->get();
        $monthTotal = $this->summarizeTrades($monthTrades);

        return view('journal.index', compact(
            'year', 'month', 'start', 'end', 'gridStart', 'gridEnd',
            'days', 'weeks', 'monthTotal'
        ));
    }

    public function dayDetails(string $date)
    {
        $d = Carbon::parse($date)->toDateString();
        $items = Trade::whereDate('trade_date', $d)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'date' => $d,
            'summary' => $this->summarizeTrades($items),
            'items' => $items,
        ]);
    }

    public function storeTrade(Request $request)
    {
        $data = $request->validate([
            'trade_date' => ['required', 'date'],

            'player1' => ['required', 'string', 'max:255'],
            'player2' => ['required', 'string', 'max:255'],
            'team1' => ['nullable', 'string', 'max:255'],
            'team2' => ['nullable', 'string', 'max:255'],

            'ht1' => ['required', 'integer', 'min:0', 'max:20'],
            'ht2' => ['required', 'integer', 'min:0', 'max:20'],

            'odds' => ['nullable', 'numeric', 'min:0'],
            'parcial1' => ['nullable', 'integer', 'min:0', 'max:20'],
            'parcial2' => ['nullable', 'integer', 'min:0', 'max:20'],

            'ft1' => ['required', 'integer', 'min:0', 'max:20'],
            'ft2' => ['required', 'integer', 'min:0', 'max:20'],

            'sp' => ['nullable', 'numeric'],
            'stake' => ['nullable', 'numeric', 'min:0'],

            'result' => ['required', 'in:tp,sl,be'],

            'profit_pct' => ['nullable', 'numeric'],
            'profit_amount' => ['nullable', 'numeric'],
        ]);

        $htTotal = (int)$data['ht1'] + (int)$data['ht2'];
        $ftTotal = (int)$data['ft1'] + (int)$data['ft2'];

        // sp = goles del 2T (FT - HT)
        $data['sp'] = $ftTotal - $htTotal;
        dd($data);
        Trade::create($data);

        return back()->with('ok', 'Operación guardada');
    }

    private function summarizeTrades($collection): array
{
    $trades = ($collection ?? collect())->values();

    $count = $trades->count();

    // Conteos base
    $wins = $trades->filter(function ($t) { return $t->wl === 'win'; })->count();
    $losses = $trades->filter(function ($t) { return $t->wl === 'loss'; })->count();
    $be = $trades->filter(function ($t) { return $t->wl === 'be'; })->count();

    // WR: wins / (wins+losses)  (BE no cuenta)
    $den = max(1, ($wins + $losses));
    $wr = ($wins / $den) * 100.0;

    // TP/SL por racha: cuenta cuando el trade i y el i-1 son iguales (win/win o loss/loss)
    // IMPORTANTE: ordena por id asc para que la secuencia sea real
    $ordered = $trades->sortBy('id')->values();

    $tp = 0;
    $sl = 0;

    for ($i = 1; $i < $ordered->count(); $i++) {
        $prev = $ordered[$i - 1];
        $curr = $ordered[$i];

        if ($prev->wl === 'win' && $curr->wl === 'win') $tp++;
        if ($prev->wl === 'loss' && $curr->wl === 'loss') $sl++;
    }

    // Profit del día (igual que antes, pero sin whereNotNull)
    $profitPctSum = $trades->filter(function ($t) { return !is_null($t->profit_pct); })->sum('profit_pct');

    $stakeSum = $trades->filter(function ($t) { return !is_null($t->stake); })->sum('stake');
    $profitAmountSum = $trades->filter(function ($t) { return !is_null($t->profit_amount); })->sum('profit_amount');

    $profitPct = $profitPctSum !== 0.0
        ? $profitPctSum
        : ($stakeSum > 0 ? ($profitAmountSum / $stakeSum) * 100.0 : 0.0);

    return [
        'trades' => $count,
        'wins' => $wins,
        'losses' => $losses,
        'be' => $be,
        'tp' => $tp,
        'sl' => $sl,
        'wr' => round($wr, 1),
        'profit_pct' => round($profitPct, 1),
        'stake_sum' => (float)$stakeSum,
        'profit_amount_sum' => (float)$profitAmountSum,
    ];
}


    private function dayColorClass(float $profitPct): string
    {
        // Reglas:
        // >=25 verde
        // 0..24 gris
        // -20..-0.1 amarillo
        // < -20 rojo
        if ($profitPct >= 25) return 'journal-day--green';
        if ($profitPct >= 0) return 'journal-day--gray';
        if ($profitPct >= -20) return 'journal-day--yellow';
        return 'journal-day--red';
    }

    private function dayBadgeClass(float $profitPct): ?string
    {
        // >=50 estrella, >=75 azul
        if ($profitPct >= 75) return 'journal-badge--blue';
        if ($profitPct >= 50) return 'journal-badge--star';
        return null;
    }
}
