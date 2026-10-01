@extends('layouts.app')

@section('content')
<link href="/css/journal.css" rel="stylesheet" type="text/css" />

<div class="journal-wrap">
    @php
        $monthLabel = \Carbon\Carbon::create($year,$month,1)->format('F Y');
        $prev = \Carbon\Carbon::create($year, $month, 1)->subMonth();
        $next = \Carbon\Carbon::create($year, $month, 1)->addMonth();
    @endphp

    <div class="journal-header">
        <div class="journal-nav">
            <a href="{{ route('journal.index', [$prev->year, $prev->month]) }}" title="Mes anterior">‹</a>
        </div>
        <div class="journal-title">{{ $monthLabel }}</div>
        <div class="journal-nav">
            <a href="{{ route('journal.index', [$next->year, $next->month]) }}" title="Mes siguiente">›</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="journal-kpi">
                <div class="journal-kpi__title">Total del mes</div>
                <div class="journal-kpi__value">{{ $monthTotal['profit_pct'] }}%</div>
                <div class="journal-kpi__meta">
                    <span><b>WR</b> {{ $monthTotal['wr'] }}%</span>
                    <span><b>TP</b> {{ $monthTotal['tp'] }}</span>
                    <span><b>SL</b> {{ $monthTotal['sl'] }}</span>
                    <span><b>BE</b> {{ $monthTotal['be'] }}</span>
                    <span><b>Ops</b> {{ $monthTotal['trades'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="journal-kpi">
                <div class="journal-kpi__title">Acciones</div>
                <div class="journal-kpi__meta" style="margin-top:6px;">
                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createTradeModal">+ Agregar operación</a>
                </div>
            </div>
        </div>
    </div>

    <div class="journal-card">
        <div class="journal-grid">
            @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat','Week'] as $d)
                <div class="journal-dow">{{ $d }}</div>
            @endforeach

            @php $cursor = $gridStart->copy(); @endphp
            @while($cursor <= $gridEnd)
                @php
                    $weekKey = $cursor->copy()->startOfWeek(\Carbon\Carbon::SUNDAY)->toDateString();
                @endphp

                @for($i=0; $i<7; $i++)
                    @php
                        $dateKey = $cursor->toDateString();
                        $cell = $days[$dateKey];
                        $s = $cell['summary'];
                        $clickable = $s['trades'] > 0;
                    @endphp

                    <div
                        class="journal-cell {{ $cell['in_month'] ? '' : 'journal-cell--muted' }} {{ $s['trades'] ? $cell['color_class'] : '' }}"
                        @if($clickable)
                            data-date="{{ $dateKey }}"
                            onclick="Journal.openDay(this)"
                        @endif
                    >
                        <div class="journal-daynum">{{ $cursor->format('d') }}</div>

                        @if($s['trades'] > 0)
                            <div class="journal-profit">{{ $s['profit_pct'] }}%</div>
                            <div class="journal-meta">
                                <span><b>WR</b> {{ $s['wr'] }}%</span>
                                <span><b>TP</b> {{ $s['tp'] }}</span>
                                <span><b>SL</b> {{ $s['sl'] }}</span>
                                <span><b>BE</b> {{ $s['be'] }}</span>
                                <span><b>Ops</b> {{ $s['trades'] }}</span>
                            </div>

                            @if($cell['badge_class'])
                                <div class="journal-badge {{ $cell['badge_class'] }}"></div>
                            @endif
                        @else
                            <div class="journal-meta"><span>0 trades</span></div>
                        @endif
                    </div>

                    @php $cursor->addDay(); @endphp
                @endfor

                @php
                    $w = $weeks[$weekKey] ?? ['profit_pct'=>0,'wr'=>0,'tp'=>0,'sl'=>0,'be'=>0,'trades'=>0];
                @endphp
                <div class="journal-cell journal-week">
                    <div class="journal-daynum">Week Total</div>
                    <div class="journal-profit">{{ $w['profit_pct'] }}%</div>
                    <div class="journal-meta">
                        <span><b>WR</b> {{ $w['wr'] }}%</span>
                        <span><b>TP</b> {{ $w['tp'] }}</span>
                        <span><b>SL</b> {{ $w['sl'] }}</span>
                        <span><b>BE</b> {{ $w['be'] }}</span>
                        <span><b>Ops</b> {{ $w['trades'] }}</span>
                    </div>
                </div>
            @endwhile
        </div>
    </div>
</div>

{{-- MODAL DETALLE --}}
<div class="modal fade" id="dayModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content" style="background:#0b0f14;color:#cfd6df;border-radius:14px;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);">
        <h5 class="modal-title" id="dayModalTitle">Detalle día</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:1;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="daySummary" class="mb-3"></div>
        <div class="table-responsive">
          <table class="table table-dark table-striped table-bordered" id="dayTable" style="width:100%;">
            <thead>
              <tr>
                <th>player1</th><th>player2</th><th>team1</th><th>team2</th>
                <th>ht1</th><th>ht2</th><th>odds</th>
                <th>parcial1</th><th>parcial2</th>
                <th>ft1</th><th>ft2</th><th>sp</th><th>stake</th><th>result</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,.08);">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL CREAR --}}
<div class="modal fade" id="createTradeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content" style="background:#0b0f14;color:#cfd6df;border-radius:14px;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);">
        <h5 class="modal-title">Agregar operación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:1;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="{{ route('journal.trade.store') }}">
        @csrf
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Fecha</label>
              <input type="date" name="trade_date" class="form-control" required value="{{ now()->toDateString() }}">
            </div>
            <div class="form-group col-md-4">
              <label>player1</label>
              <input name="player1" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
              <label>player2</label>
              <input name="player2" class="form-control" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>team1</label>
              <input name="team1" class="form-control">
            </div>
            <div class="form-group col-md-6">
              <label>team2</label>
              <input name="team2" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-2"><label>ht1</label><input type="number" name="ht1" class="form-control" required min="0" max="20" value="0"></div>
            <div class="form-group col-md-2"><label>ht2</label><input type="number" name="ht2" class="form-control" required min="0" max="20" value="0"></div>
            <div class="form-group col-md-2"><label>odds</label><input type="number" step="0.01" name="odds" class="form-control"></div>
            <div class="form-group col-md-3"><label>parcial1</label><input type="number" name="parcial1" class="form-control" min="0" max="20"></div>
            <div class="form-group col-md-3"><label>parcial2</label><input type="number" name="parcial2" class="form-control" min="0" max="20"></div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-2"><label>ft1</label><input type="number" name="ft1" class="form-control" required min="0" max="20" value="0"></div>
            <div class="form-group col-md-2"><label>ft2</label><input type="number" name="ft2" class="form-control" required min="0" max="20" value="0"></div>
            <div class="form-group col-md-3"><label>sp</label><input type="number" step="0.01" name="sp" class="form-control"></div>
            <div class="form-group col-md-3"><label>stake</label><input type="number" step="0.01" name="stake" class="form-control"></div>
            <div class="form-group col-md-2">
              <select name="wl" class="form-select" required>
                <option value="win">WIN</option>
                <option value="loss">LOSS</option>
                <option value="be">BE</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>profit_pct (opcional)</label>
              <input type="number" step="0.01" name="profit_pct" class="form-control" placeholder="Ej: 12.5">
            </div>
            <div class="form-group col-md-6">
              <label>profit_amount (opcional)</label>
              <input type="number" step="0.01" name="profit_amount" class="form-control" placeholder="Ej: 15000">
            </div>
          </div>

          <small style="color:#8f99a7;">
            Si no ingresas profit_pct, el % del día se calcula con profit_amount / sum(stake) del día.
          </small>
        </div>

        <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,.08);">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/js/journal.js"></script>
@endsection
