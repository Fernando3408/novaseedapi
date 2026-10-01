@extends('layouts.app')
@section('content')
<?php $titulo = "{{ $player1 }} vs {{ $player2 }}"; ?>
<title><?= htmlspecialchars($titulo) ?></title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container text-center my-4">
  <form method="GET" action="{{ url('bets_compare') }}">
    <h2>{{ $player1 }} vs {{ $player2 }}</h2>
    <div class="row justify-content-center mt-3">
      <div class="col-md-4">
        <label for="ht1">{{ $player1 }} Goles HT</label>
        <input type="number" name="ht1" id="ht1" class="form-control" value="{{ request('ht1') }}">
        <select name="team1" id="team1" class="form-control" style="margin-top:25px;">
          <option value="">-- Selecciona equipo --</option>
          @foreach($teams as $cleaned => $original)
            <option value="{{ $cleaned }}">{{ $original }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label for="ht2">{{ $player2 }} Goles HT</label>
        <input type="number" name="ht2" id="ht2" class="form-control" value="{{ request('ht2') }}">
        <select name="team2" id="team2" class="form-control" style="margin-top:25px;">
          <option value="">-- Selecciona equipo --</option>
          @foreach($teams as $cleaned => $original)
            <option value="{{ $cleaned }}">{{ $original }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <input type="hidden" name="player1" value="{{ $player1 }}">
    <input type="hidden" name="player2" value="{{ $player2 }}">
    <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
  </form>
</div>

<!-- Modal Detalle Partidos -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detalle de Partidos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"><div id="modalDetailContent"></div></div>
    </div>
  </div>
</div>

<style>
@media (min-width:1400px){ .m-container.m-container--responsive.m-container--xxl{ width:100vw!important; } }
</style>

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
  <div class="m-portlet__body">
    <div class="row">

      <!-- Tabla 1: Player 1 -->
      <div class="col-md-6">
        <table class="dttable table table-striped- table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th>Tipo</th><th>Player</th><th>Partidos</th>
              <th>0.5%</th><th>1.5%</th><th>2.5%</th><th>3.5%</th>
              <th>4.5%</th><th>5.5%</th><th>6.5%</th>
              <th>SP 0.5%</th><th>SP 1.5%</th><th>SP 2.5%</th><th>SP 3.5%</th>
              <th>SP 4.5%</th><th>SP 5.5%</th><th>SP 6.5%</th>
            </tr>
          </thead>
          <tbody>
          @if(!empty(@$data_p1))
            @foreach(@$data_p1 as $period => $dx)
              @foreach($dx as $p1d1)
              @php
                $p1d1 = (array)$p1d1;
                if($p1d1['partidos_jugados'] > 0){
                  @$por_05_p1 = round($p1d1['zero']*100/$p1d1['partidos_jugados']);
                  @$por_15_p1 = round($p1d1['one']*100/$p1d1['partidos_jugados']);
                  @$por_25_p1 = round($p1d1['two']*100/$p1d1['partidos_jugados']);
                  @$por_35_p1 = round($p1d1['tree']*100/$p1d1['partidos_jugados']);
                  @$por_45_p1 = round($p1d1['four']*100/$p1d1['partidos_jugados']);
                  @$por_55_p1 = round($p1d1['five']*100/$p1d1['partidos_jugados']);
                  @$por_65_p1 = round($p1d1['six']*100/$p1d1['partidos_jugados']);
                  @$por_05sp_p1 = round($p1d1['spzero']*100/$p1d1['partidos_jugados']);
                  @$por_15sp_p1 = round($p1d1['spone']*100/$p1d1['partidos_jugados']);
                  @$por_25sp_p1 = round($p1d1['sptwo']*100/$p1d1['partidos_jugados']);
                  @$por_35sp_p1 = round($p1d1['sptree']*100/$p1d1['partidos_jugados']);
                  @$por_45sp_p1 = round($p1d1['spfour']*100/$p1d1['partidos_jugados']);
                  @$por_55sp_p1 = round($p1d1['spfive']*100/$p1d1['partidos_jugados']);
                  @$por_65sp_p1 = round($p1d1['spsix']*100/$p1d1['partidos_jugados']);
                }
              @endphp
              <tr>
                <td>{{ $period }}</td>
                <td>{{ @$player1 }}</td>
                <td>
                  <a href="#" class="show-detail"
                     data-period="{{ $period }}" data-player="p1"
                     data-player1="{{ $player1 }}" data-player2="{{ $player2 }}"
                     data-ht1="{{ request('ht1') }}" data-ht2="{{ request('ht2') }}"
                     data-url="{{ route('bets.detail') }}">
                     {{ $p1d1['partidos_jugados'] }}
                  </a>
                </td>
                <td>{{ round(@$por_05_p1) }}</td><td>{{ round(@$por_15_p1) }}</td><td>{{ round(@$por_25_p1) }}</td>
                <td>{{ round(@$por_35_p1) }}</td><td>{{ round(@$por_45_p1) }}</td><td>{{ round(@$por_55_p1) }}</td>
                <td>{{ round(@$por_65_p1) }}</td>
                <td>{{ round(@$por_05sp_p1) }}</td><td>{{ round(@$por_15sp_p1) }}</td><td>{{ round(@$por_25sp_p1) }}</td>
                <td>{{ round(@$por_35sp_p1) }}</td><td>{{ round(@$por_45sp_p1) }}</td><td>{{ round(@$por_55sp_p1) }}</td>
                <td>{{ round(@$por_65sp_p1) }}</td>
              </tr>
              @endforeach
            @endforeach
          @endif
          </tbody>
        </table>
      </div>

      <!-- Tabla 2: Player 2 -->
      <div class="col-md-6">
        <table class="dttable table table-striped- table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th>Player</th><th>Partidos</th>
              <th>0.5%</th><th>1.5%</th><th>2.5%</th><th>3.5%</th>
              <th>4.5%</th><th>5.5%</th><th>6.5%</th>
              <th>SP 0.5%</th><th>SP 1.5%</th><th>SP 2.5%</th><th>SP 3.5%</th>
              <th>SP 4.5%</th><th>SP 5.5%</th><th>SP 6.5%</th>
            </tr>
          </thead>
          <tbody>
          @if(!empty(@$data_p2))
            @foreach(@$data_p2 as $period => $dx)
              @foreach($dx as $p1d2)
              @php
                $p1d2 = (array)$p1d2;
                if($p1d2['partidos_jugados'] > 0){
                  @$por_05_p2 = round($p1d2['zero']*100/$p1d2['partidos_jugados']);
                  @$por_15_p2 = round($p1d2['one']*100/$p1d2['partidos_jugados']);
                  @$por_25_p2 = round($p1d2['two']*100/$p1d2['partidos_jugados']);
                  @$por_35_p2 = round($p1d2['tree']*100/$p1d2['partidos_jugados']);
                  @$por_45_p2 = round($p1d2['four']*100/$p1d2['partidos_jugados']);
                  @$por_55_p2 = round($p1d2['five']*100/$p1d2['partidos_jugados']);
                  @$por_65_p2 = round($p1d2['six']*100/$p1d2['partidos_jugados']);
                  @$por_05sp_p2 = round($p1d2['spzero']*100/$p1d2['partidos_jugados']);
                  @$por_15sp_p2 = round($p1d2['spone']*100/$p1d2['partidos_jugados']);
                  @$por_25sp_p2 = round($p1d2['sptwo']*100/$p1d2['partidos_jugados']);
                  @$por_35sp_p2 = round($p1d2['sptree']*100/$p1d2['partidos_jugados']);
                  @$por_45sp_p2 = round($p1d2['spfour']*100/$p1d2['partidos_jugados']);
                  @$por_55sp_p2 = round($p1d2['spfive']*100/$p1d2['partidos_jugados']);
                  @$por_65sp_p2 = round($p1d2['spsix']*100/$p1d2['partidos_jugados']);
                }
              @endphp
              <tr>
                <td>{{ @$player2 }}</td>
                <td>
                  <a href="#" class="show-detail"
                     data-period="{{ $period }}" data-player="p2"
                     data-player1="{{ $player1 }}" data-player2="{{ $player2 }}"
                     data-ht1="{{ request('ht1') }}" data-ht2="{{ request('ht2') }}"
                     data-url="{{ route('bets.detail') }}">
                     {{ $p1d2['partidos_jugados'] }}
                  </a>
                </td>
                <td>{{ round(@$por_05_p2) }}</td><td>{{ round(@$por_15_p2) }}</td><td>{{ round(@$por_25_p2) }}</td>
                <td>{{ round(@$por_35_p2) }}</td><td>{{ round(@$por_45_p2) }}</td><td>{{ round(@$por_55_p2) }}</td>
                <td>{{ round(@$por_65_p2) }}</td>
                <td>{{ round(@$por_05sp_p2) }}</td><td>{{ round(@$por_15sp_p2) }}</td><td>{{ round(@$por_25sp_p2) }}</td>
                <td>{{ round(@$por_35sp_p2) }}</td><td>{{ round(@$por_45sp_p2) }}</td><td>{{ round(@$por_55sp_p2) }}</td>
                <td>{{ round(@$por_65sp_p2) }}</td>
              </tr>
              @endforeach
            @endforeach
          @endif
          </tbody>
        </table>
      </div>

      <!-- Tabla 3: Global con HT -->
      <div class="col-md-12">
        <table id="tableGlobalHT" class="dttable table table-striped- table-bordered table-hover table-checkable global_partidos">
          <thead>
            <tr>
              <th>Dias</th><th>Playerzzz</th><th>Enemigo</th><th>Partidos</th>
              <th>0.5%</th><th>1.5%</th><th>2.5%</th><th>3.5%</th>
              <th>4.5%</th><th>5.5%</th><th>6.5%</th>
              <th>SP 0.5%</th><th>SP 1.5%</th><th>SP 2.5%</th><th>SP 3.5%</th>
              <th>SP 4.5%</th><th>SP 5.5%</th><th>SP 6.5%</th>
            </tr>
          </thead>
          <tbody>
          @if(!empty($global_partidos))
            @foreach($global_partidos as $bet2ene)
              @php $bet2ene = (array)$bet2ene; @endphp
              @if(!empty($bet2ene['partidos_jugados']) && $bet2ene['partidos_jugados'] > 0)
                @php
                  @$por_05 = round($bet2ene['zero']*100/$bet2ene['partidos_jugados']);
                  @$por_15 = round($bet2ene['one']*100/$bet2ene['partidos_jugados']);
                  @$por_25 = round($bet2ene['two']*100/$bet2ene['partidos_jugados']);
                  @$por_35 = round($bet2ene['tree']*100/$bet2ene['partidos_jugados']);
                  @$por_45 = round($bet2ene['four']*100/$bet2ene['partidos_jugados']);
                  @$por_55 = round($bet2ene['five']*100/$bet2ene['partidos_jugados']);
                  @$por_65 = round($bet2ene['six']*100/$bet2ene['partidos_jugados']);
                  @$sp_05 = round($bet2ene['spzero']*100/$bet2ene['partidos_jugados']);
                  @$sp_15 = round($bet2ene['spone']*100/$bet2ene['partidos_jugados']);
                  @$sp_25 = round($bet2ene['sptwo']*100/$bet2ene['partidos_jugados']);
                  @$sp_35 = round($bet2ene['sptree']*100/$bet2ene['partidos_jugados']);
                  @$sp_45 = round($bet2ene['spfour']*100/$bet2ene['partidos_jugados']);
                  @$sp_55 = round($bet2ene['spfive']*100/$bet2ene['partidos_jugados']);
                  @$sp_65 = round($bet2ene['spsix']*100/$bet2ene['partidos_jugados']);
                @endphp
                <tr>
                  <td>{{ $bet2ene['umbral'] }}</td>
                  <td>{{ @$player1 }}</td>
                  <td>{{ @$player2 }}</td>
                  <td>
                    <a href="#" class="show-detail"
                       data-period="global" data-player="global"
                       data-player1="{{ $player1 }}" data-player2="{{ $player2 }}"
                       data-ht1="{{ request('ht1') }}" data-ht2="{{ request('ht2') }}"
                       data-url="{{ route('bets.detail') }}">
                       {{ $bet2ene['partidos_jugados'] }}
                    </a>
                  </td>
                  <td>{{ round(@$por_05) }}</td><td>{{ round(@$por_15) }}</td><td>{{ round(@$por_25) }}</td>
                  <td>{{ round(@$por_35) }}</td><td>{{ round(@$por_45) }}</td><td>{{ round(@$por_55) }}</td>
                  <td>{{ round(@$por_65) }}</td>
                  <td>{{ round(@$sp_05) }}</td><td>{{ round(@$sp_15) }}</td><td>{{ round(@$sp_25) }}</td>
                  <td>{{ round(@$sp_35) }}</td><td>{{ round(@$sp_45) }}</td><td>{{ round(@$sp_55) }}</td>
                  <td>{{ round(@$sp_65) }}</td>
                </tr>
              @endif
            @endforeach
          @endif
          </tbody>
        </table>
      </div>

      <!-- Tabla 4: Global sin HT -->
      <div class="col-md-12">
        <table id="tableGlobalNoHT" class="dttable table table-striped- table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th>Dias</th><th>Playeryyy</th><th>Enemigo</th><th>Partidos</th>
              <th>0.5%</th><th>1.5%</th><th>2.5%</th><th>3.5%</th>
              <th>4.5%</th><th>5.5%</th><th>6.5%</th>
              <th>SP 0.5%</th><th>SP 1.5%</th><th>SP 2.5%</th><th>SP 3.5%</th>
              <th>SP 4.5%</th><th>SP 5.5%</th><th>SP 6.5%</th>
            </tr>
          </thead>
          <tbody>
          @if(!empty($global_partidos_sin_ht))
            @foreach($global_partidos_sin_ht as $bet2ene_sin_ht)
              @php $bet2ene_sin_ht = (array)$bet2ene_sin_ht; @endphp
              @if(!empty($bet2ene_sin_ht['partidos_jugados']) && $bet2ene_sin_ht['partidos_jugados'] > 0)
                @php
                  @$por_05 = round($bet2ene_sin_ht['zero']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$por_15 = round($bet2ene_sin_ht['one']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$por_25 = round($bet2ene_sin_ht['two']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$por_35 = round($bet2ene_sin_ht['tree']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$por_45 = round($bet2ene_sin_ht['four']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$por_55 = round($bet2ene_sin_ht['five']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$por_65 = round($bet2ene_sin_ht['six']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$sp_05 = round($bet2ene_sin_ht['spzero']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$sp_15 = round($bet2ene_sin_ht['spone']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$sp_25 = round($bet2ene_sin_ht['sptwo']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$sp_35 = round($bet2ene_sin_ht['sptree']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$sp_45 = round($bet2ene_sin_ht['spfour']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$sp_55 = round($bet2ene_sin_ht['spfive']*100/$bet2ene_sin_ht['partidos_jugados']);
                  @$sp_65 = round($bet2ene_sin_ht['spsix']*100/$bet2ene_sin_ht['partidos_jugados']);
                @endphp
                <tr>
                  <td>{{ $bet2ene_sin_ht['umbral'] }}</td>
                  <td>{{ @$player1 }}</td>
                  <td>{{ @$player2 }}</td>
                  <td>
                    <a href="#" class="show-detail"
                       data-period="global" data-player="global"
                       data-player1="{{ $player1 }}" data-player2="{{ $player2 }}"
                       data-ht1="{{ request('ht1') }}" data-ht2="{{ request('ht2') }}"
                       data-url="{{ route('bets.detail') }}">
                       {{ $bet2ene_sin_ht['partidos_jugados'] }}
                    </a>
                  </td>
                  <td>{{ round(@$por_05) }}</td><td>{{ round(@$por_15) }}</td><td>{{ round(@$por_25) }}</td>
                  <td>{{ round(@$por_35) }}</td><td>{{ round(@$por_45) }}</td><td>{{ round(@$por_55) }}</td>
                  <td>{{ round(@$por_65) }}</td>
                  <td>{{ round(@$sp_05) }}</td><td>{{ round(@$sp_15) }}</td><td>{{ round(@$sp_25) }}</td>
                  <td>{{ round(@$sp_35) }}</td><td>{{ round(@$sp_45) }}</td><td>{{ round(@$sp_55) }}</td>
                  <td>{{ round(@$sp_65) }}</td>
                </tr>
              @endif
            @endforeach
          @endif
          </tbody>
        </table>
      </div>

      <!-- Historiales -->
      <div class="col-md-6">
        <table class="dttable table table-striped- table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th>Player1</th><th>Player2</th><th>Team 1</th><th>Team 2</th>
              <th>Fecha</th><th>Hora</th><th>HT1</th><th>HT2</th><th>HT</th>
              <th>F1</th><th>F2</th><th>FT</th><th>SP</th>
            </tr>
          </thead>
          <tbody>
          @if(!empty($vs_p1) && @$vs_p1[0]->player1 != null)
            @foreach($vs_p1 as $p1)
              @php $p1 = (array)$p1; @endphp
              <tr>
                <td>{{ @$p1['player1'] }}</td><td>{{ @$p1['player2'] }}</td>
                <td>{{ @$p1['team1'] }}</td><td>{{ @$p1['team2'] }}</td>
                <td>{{ @$p1['fecha'] }}</td><td>{{ @$p1['hora'] }}</td>
                <td>{{ @$p1['ht1'] }}</td><td>{{ @$p1['ht2'] }}</td>
                <td>{{ (@$p1['ht1'] + @$p1['ht2']) }}</td>
                <td>{{ @$p1['ft1'] }}</td><td>{{ @$p1['ft2'] }}</td>
                <td>{{ (@$p1['ft1'] + @$p1['ft2']) }}</td>
                <td>{{ ((@$p1['ft1'] + @$p1['ft2']) - (@$p1['ht1'] + @$p1['ht2'])) }}</td>
              </tr>
            @endforeach
          @endif
          </tbody>
        </table>
      </div>

      <div class="col-md-6">
        <table class="dttable table table-striped- table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th>Player1</th><th>Player2</th><th>Team 1</th><th>Team 2</th>
              <th>Fecha</th><th>Hora</th><th>HT1</th><th>HT2</th><th>HT</th>
              <th>F1</th><th>F2</th><th>FT</th><th>SP</th>
            </tr>
          </thead>
          <tbody>
          @if(!empty($vs_p2) && @$vs_p2[0]->player2 != null)
            @foreach($vs_p2 as $p2)
              @php $p2 = (array)$p2; @endphp
              <tr>
                <td>{{ @$p2['player1'] }}</td><td>{{ @$p2['player2'] }}</td>
                <td>{{ @$p2['team1'] }}</td><td>{{ @$p2['team2'] }}</td>
                <td>{{ @$p2['fecha'] }}</td><td>{{ @$p2['hora'] }}</td>
                <td>{{ @$p2['ht1'] }}</td><td>{{ @$p2['ht2'] }}</td>
                <td>{{ (@$p2['ht1'] + @$p2['ht2']) }}</td>
                <td>{{ @$p2['ft1'] }}</td><td>{{ @$p2['ft2'] }}</td>
                <td>{{ (@$p2['ft1'] + @$p2['ft2']) }}</td>
                <td>{{ ((@$p2['ft1'] + @$p2['ft2']) - (@$p2['ht1'] + @$p2['ht2'])) }}</td>
              </tr>
            @endforeach
          @endif
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

{{-- Controles del Asesor GPT --}}
<div class="container my-4">
  <div class="row align-items-end">
    <div class="col-md-3">
      <label class="mb-1">Market a evaluar</label>
      <select id="advisorMarket" class="form-control">
        <option value="0.5">0.5</option><option value="1.5">1.5</option>
        <option value="2.5">2.5</option><option value="3.5" selected>3.5</option>
        <option value="4.5">4.5</option><option value="5.5">5.5</option>
        <option value="6.5">6.5</option>
      </select>
      <small class="text-muted">Market FT (Over/Under x.5).</small>
    </div>
    <div class="col-md-3">
      <label class="mb-1">Minuto (ref.)</label>
      <input id="advisorMinute" type="number" class="form-control" value="40" min="0">
    </div>
    <div class="col-md-3">
      <label class="mb-1">Sentido</label>
      <select id="advisorSense" class="form-control">
        <option value="UNDER">UNDER</option>
        <option value="OVER" selected>OVER</option>
      </select>
      <small class="text-muted">Afecta la métrica objetivo y el SP dinámico.</small>
    </div>
    <div class="col-md-3">
      <label class="mb-1 d-block">&nbsp;</label>
      <button id="btnAdvisor" class="btn btn-primary btn-block">Evaluar Señal (GPT)</button>
    </div>
  </div>
</div>

{{-- Modal del Asesor GPT --}}
<div class="modal fade" id="advisorModal" tabindex="-1" role="dialog" aria-labelledby="advisorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width:900px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Asesor GPT — <span id="advisorTitle">{{ $player1 }} vs {{ $player2 }}</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="advisorLoading" class="alert alert-info py-2" style="display:none;">Evaluando señal…</div>
        <div class="d-flex align-items-center mb-3">
          <div class="mr-3"><strong>Decisión:</strong> <span id="advDecision">—</span></div>
          <div class="mr-3"><strong>Probabilidad:</strong> <span id="advProb">—</span></div>
          <div class="mr-3"><strong>Confianza:</strong> <span id="advConf">—</span></div>
          <div class="ml-auto"><span id="advBadge" class="badge badge-secondary">—</span></div>
        </div>
        <div class="row">
          <div class="col-md-6"><h6>Razones</h6><ul id="advReasons" class="mb-3"></ul></div>
          <div class="col-md-6"><h6>Riesgos</h6><ul id="advRisks" class="mb-3"></ul></div>
        </div>
        <div class="small text-muted">
          <strong>Payload usado:</strong>
          <pre id="advPayload" class="mb-0" style="white-space:pre-wrap;"></pre>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ====== JS ====== -->
<script>
$(function(){
  // modal detalle
  $('.show-detail').click(function(e){
    e.preventDefault();
    $.ajax({
      url: $(this).data('url'), type: 'GET',
      data: {
        period: $(this).data('period'), player: $(this).data('player'),
        player1: $(this).data('player1'), player2: $(this).data('player2'),
        ht1: $(this).data('ht1'), ht2: $(this).data('ht2')
      },
      success: function(resp){ $('#modalDetailContent').html(resp); $('#detailModal').modal('show'); },
      error: function(){ $('#modalDetailContent').html('<p>Error al cargar los detalles.</p>'); $('#detailModal').modal('show'); }
    });
  });

  // DataTables
  $(".dttable").DataTable({ scrollX:true, paginate:false, info:false });

  // title
  document.title = (@json($player1))+' vs '+(@json($player2))+' | Comparar';
});
</script>

<script>
/* ===================== ASESOR GPT: lectura tablas + OVER/UNDER + SP dinámico + confianza ===================== */
(function(){

  function txt(el){ return (el && el.textContent!=null) ? el.textContent.trim() : ''; }
  function pctToProb(x){
    if (x==null) return null;
    if (typeof x==='string') x = x.replace('%','').trim();
    var v = parseFloat(x); if (isNaN(v)) return null;
    return (v>1)? v/100.0 : v; // 0..1
  }

  // Normaliza etiquetas de ventana (evita 30d -> 3d)
  function normalizeWindow(label){
    label = (''+label).trim().toLowerCase();
    if (label === 'hoy' || label === 'today' || label === '0' || label === '0d') return 'today';
    if (label === 'global' || label === 'all' || label === '∞') return 'all';
    label = label.replace(/\s*(d[ií]as|dias|día|dia|d)\s*$/i, 'd').replace(/\s+/g, '');
    if (/^30d?$/.test(label)) return '30d';
    if (/^14d?$/.test(label)) return '14d';
    if (/^10d?$/.test(label)) return '10d';
    if (/^7d?$/.test(label))  return '7d';
    if (/^3d?$/.test(label))  return '3d';
    var n = parseInt(label,10);
    if (!isNaN(n)){
      if (n===0) return 'today';
      if (n===3) return '3d';
      if (n===7) return '7d';
      if (n===10) return '10d';
      if (n===14) return '14d';
      if (n===30) return '30d';
    }
    return label;
  }

  function headerIndexMap($t){
    const m={}; $t.querySelectorAll('thead th').forEach((th,i)=>m[txt(th).toLowerCase()]=i);
    return m;
  }
  function findColIdx(map, labels){
    for (let l of labels){ const k=(l||'').toLowerCase(); if (map.hasOwnProperty(k)) return map[k]; }
    const canon = labels.map(s=>(s||'').toLowerCase().replace(/\s+/g,''));
    for (const key in map){ const norm = key.replace(/\s+/g,''); if (canon.includes(norm)) return map[key]; }
    return -1;
  }

  // Lee OVER desde tabla; deriva UNDER = 100 - OVER. Lee TODOS los SP X.5 como mapa sp_over{ "0.5":%, ... }
  function extractGlobalRows($table, marketStr){
    const map = headerIndexMap($table);
    const idxDias     = findColIdx(map, ['dias','días','umbral']);
    const idxPart     = findColIdx(map, ['partidos']);
    const header      = marketStr.endsWith('%')? marketStr : (marketStr+'%');
    const idxOverMkt  = findColIdx(map, [header, marketStr]);

    // detectar columnas SP x.5
    const spCols = {}; // "0.5"->idx, "1.5"->idx, ...
    Object.keys(map).forEach(k=>{
      const m = k.match(/^sp\s*([0-9]+(?:\.[05])?)%?$/i);
      if (m){
        const key = (m[1]+'').trim();
        spCols[key] = map[k];
      }
    });

    const out = {};
    $table.querySelectorAll('tbody tr').forEach(tr=>{
      const tds = tr.querySelectorAll('td'); if (!tds.length) return;
      const key = normalizeWindow(idxDias>=0 ? txt(tds[idxDias]) : '');
      const matches = parseInt((idxPart>=0 ? txt(tds[idxPart]) : '').replace(/\D+/g,''),10);
      if (!key || isNaN(matches)) return;

      const overTxt = (idxOverMkt>=0 ? txt(tds[idxOverMkt]) : null);
      const pOver   = pctToProb(overTxt);                   // 0..1
      const pUnder  = (pOver==null)? null : (1 - pOver);    // 0..1

      // mapa SP
      const sp_over = {};
      Object.keys(spCols).forEach(lbl=>{
        const i = spCols[lbl];
        const v = (i>=0 ? pctToProb(txt(tds[i])) : null);
        sp_over[lbl] = (v!=null ? v*100 : null); // guardamos 0..100
      });

      out[key] = {
        window: key,
        matches: matches,
        market_over: (pOver!=null? pOver*100 : null),      // 0..100
        market_under: (pUnder!=null? pUnder*100 : null),   // 0..100
        sp_over: sp_over
      };
    });
    return out;
  }

  function weightedAvg(series, field){
    let num=0, den=0;
    Object.keys(series).forEach(k=>{
      const r=series[k], n=parseFloat(r.matches), v=r[field];
      if (!isNaN(n)&&n>0&&v!=null){ const vp=(v>1? v/100 : v); num += n*vp; den += n; }
    });
    if (den<=0) return null;
    return num/den; // 0..1
  }
  function clamp(x,a,b){ return Math.max(a,Math.min(b,x)); }
  function badgeForDecision(dec){
    if(dec==='TAKE')return'badge-success';
    if(dec==='NO_SIGNAL')return'badge-warning';
    return'badge-danger';
  }

  function showAdvisorModal(res, payload){
    $('#advisorLoading').hide();
    const confBack = (res.confianza!=null)? res.confianza : null;
    const confShow = (confBack!=null)? confBack : (payload.confianza_sugerida ?? null);

    $('#advDecision').text(res.decision || '—');
    $('#advProb').text((res.probabilidad!=null)? (res.probabilidad*100).toFixed(1)+'%' : '—');
    $('#advConf').text((confShow!=null)? (confShow*100).toFixed(0)+'%' : '—');
    $('#advBadge').removeClass('badge-secondary badge-success badge-warning badge-danger')
                  .addClass(badgeForDecision(res.decision))
                  .text(res.decision || '—');

    var $reasons = $('#advReasons').empty();
    (res.explicacion && res.explicacion.racional ? res.explicacion.racional : []).forEach(r=> $reasons.append('<li>'+r+'</li>'));
    var $risks = $('#advRisks').empty();
    (res.explicacion && res.explicacion.riesgos ? res.explicacion.riesgos : []).forEach(r=> $risks.append('<li>'+r+'</li>'));
    $('#advPayload').text(JSON.stringify(payload, null, 2));
    $('#advisorModal').modal('show');
  }

  $('#btnAdvisor').on('click', function(e){
    e.preventDefault();

    const market = $('#advisorMarket').val();        // "3.5", "5.5", ...
    const marketHeader = market + '%';
    const sense  = ($('#advisorSense').val() || 'UNDER').toUpperCase(); // UNDER/OVER

    const tblHT   = document.querySelector('#tableGlobalHT');
    const tblNoHT = document.querySelector('#tableGlobalNoHT');
    if (!tblHT || !tblNoHT){ alert('Faltan tablas Global con HT (#tableGlobalHT) o sin HT (#tableGlobalNoHT).'); return; }

    // 1) Extraer TODAS las ventanas (siempre OVER en tabla; UNDER = 100 - OVER)
    const seriesHT   = extractGlobalRows(tblHT,   marketHeader);
    const seriesNoHT = extractGlobalRows(tblNoHT, marketHeader);

    // 2) Métrica objetivo según sentido
    const FIELD = (sense === 'OVER') ? 'market_over' : 'market_under';
    const p_target_ht     = weightedAvg(seriesHT,   FIELD); // 0..1
    const p_target_global = weightedAvg(seriesNoHT, FIELD); // 0..1

    // 3) SP dinámico según HT + market + sentido
    const ht_total = (parseInt($('#ht1').val()||0) + parseInt($('#ht2').val()||0));
    const marketNum = parseFloat(market);

    function weightedAvgSP(series, spLabel){
      let num=0, den=0;
      Object.keys(series).forEach(k=>{
        const r=series[k];
        const n=parseFloat(r.matches);
        const spMap = r.sp_over || {};
        const v = spMap[spLabel];
        if (!isNaN(n)&&n>0&&v!=null){
          const vp = (v>1? v/100 : v);
          num += n*vp; den += n;
        }
      });
      if (den<=0) return null;
      return num/den; // 0..1
    }

    let sp_aux = null;
    if (sense === 'OVER'){
      const needed = Math.max(0, Math.ceil(marketNum - ht_total)); // goles que faltan
      if (needed <= 0){
        sp_aux = 1.0;
      } else {
        const lbl = (needed - 0.5).toFixed(1); // "0.5","1.5",...
        const sp_ht   = weightedAvgSP(seriesHT,   lbl);
        const sp_glob = weightedAvgSP(seriesNoHT, lbl);
        sp_aux = (sp_ht!=null && sp_glob!=null) ? (0.5*sp_ht + 0.5*sp_glob)
               : (sp_ht ?? sp_glob ?? null);
        if (sp_aux == null) sp_aux = 0.30; // prior
      }
    } else { // UNDER
      const maxAllowed = Math.floor((marketNum - 0.5) - ht_total); // FT máx - HT
      if (maxAllowed < 0){
        sp_aux = 0.0;
      } else {
        const undesiredAtLeast = maxAllowed + 1; // goles en 2T que rompen el UNDER
        const lbl = (undesiredAtLeast - 0.5).toFixed(1);
        const sp_bad_ht   = weightedAvgSP(seriesHT,   lbl);
        const sp_bad_glob = weightedAvgSP(seriesNoHT, lbl);
        let sp_bad = (sp_bad_ht!=null && sp_bad_glob!=null) ? (0.5*sp_bad_ht + 0.5*sp_bad_glob)
                     : (sp_bad_ht ?? sp_bad_glob ?? null);
        if (sp_bad == null) sp_bad = 0.30;
        sp_aux = 1 - sp_bad;
      }
    }

    // 4) Tamaños de muestra y acuerdo
    const n_ht     = Object.values(seriesHT).reduce((s,r)=>s+(r.matches||0),0);
    const n_global = Object.values(seriesNoHT).reduce((s,r)=>s+(r.matches||0),0);
    const gap = Math.abs((p_target_ht||0) - (p_target_global||0));   // 0..1
    const agreePenalty = 1 - Math.min(gap, 0.25)/0.25;               // 1..0
    const sizeHt = Math.min(1, n_ht/12), sizeGl = Math.min(1, n_global/20);
    const sizeFactor = 0.5*sizeHt + 0.5*sizeGl;                      // 0..1

    // 5) Confianza sugerida
    const avgTarget = ((p_target_ht||0) + (p_target_global||0)) / 2; // 0..1
    const baseMix   = 0.6*avgTarget + 0.4*sp_aux;                    // 0..1
    const stability = 0.5 + 0.5*(agreePenalty*sizeFactor);           // 0.5..1
    const confianza_calc = clamp(baseMix * stability, 0, 1);         // 0..1

    // 6) Serializar a arrays (preserva 30d ≠ 3d)
    function seriesToArray(series){
      const keys = Object.keys(series);
      const order = ['today','3d','7d','14d','30d','90d','all'];
      const rest  = keys.filter(k=>order.indexOf(k)===-1).sort();
      const final = order.filter(k=>keys.indexOf(k)!==-1).concat(rest);
      return final.map(k => ({
        window: k,
        matches: series[k].matches,
        market_under: series[k].market_under, // 0..100
        market_over:  series[k].market_over,  // 0..100
        sp_over:  series[k].sp_over || null
      }));
    }
    const tabla_ht     = seriesToArray(seriesHT);
    const tabla_global = seriesToArray(seriesNoHT);

    if (p_target_ht==null || p_target_global==null){
      $('#advisorModal').modal('show'); $('#advisorLoading').hide();
      $('#advDecision').text('NO_SIGNAL');
      $('#advReasons').html('<li>No se pudo calcular la probabilidad objetivo (revisa headers/market).</li>');
      $('#advPayload').text(JSON.stringify({ sense, tabla_ht, tabla_global }, null, 2));
      return;
    }

    // 7) Payload final (sin históricos por ahora)
    const players = { p1: @json($player1), p2: @json($player2) };
    const payloadForBackend = {
      contexto: {
        ht1: parseInt($('#ht1').val()||0),
        ht2: parseInt($('#ht2').val()||0),
        market: sense + '_' + market.replace('.','_'), // UNDER_3_5 | OVER_3_5
        sentido: sense,
        minuto: parseInt($('#advisorMinute').val()||40),
        version_juego: 'vNueva',
        fecha: (new Date()).toISOString().slice(0,10),
        players: players,
        teams:  { t1: $("#team1 option:selected").text(), t2: $("#team2 option:selected").text() }
      },
      tabla_ht: tabla_ht,
      tabla_global: tabla_global,
      h2h_last100: [],
      last10_p1:   [],
      last10_p2:   [],
      // features agregados
      p_objetivo_ht:     p_target_ht,     // 0..1 (OVER u UNDER según 'sentido')
      p_objetivo_global: p_target_global, // 0..1
      n_ht: n_ht,
      n_global: n_global,
      sp_auxiliar: sp_aux,                // 0..1 — SP dinámico
      confianza_sugerida: confianza_calc  // 0..1
    };

    // Para auditoría rápida del SP usado:
    payloadForBackend.metadatos = {
      sp_dynamic_used: sp_aux,
      sp_ref_label: (function(){
        if (sense==='OVER'){
          const needed = Math.max(0, Math.ceil(marketNum - ht_total));
          return (needed<=0) ? '0.0' : (needed - 0.5).toFixed(1);
        } else {
          const maxAllowed = Math.floor((marketNum - 0.5) - ht_total);
          const undesiredAtLeast = maxAllowed + 1;
          return (undesiredAtLeast - 0.5).toFixed(1);
        }
      })()
    };

    // 8) Mostrar modal y POST JSON
    $('#advisorLoading').show();
    $('#advDecision,#advProb,#advConf,#advBadge').text('—');
    $('#advReasons').empty(); $('#advRisks').empty(); $('#advPayload').text('');
    $('#advisorModal').modal('show');

    $.ajax({
      url: "{{ url('/advisor') }}",
      type: 'POST',
      data: JSON.stringify(payloadForBackend),
      contentType: 'application/json; charset=utf-8',
      processData: false,
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: function(res){ showAdvisorModal(res || {}, payloadForBackend); },
      error: function(){
        $('#advisorLoading').hide();
        $('#advDecision').text('ERROR');
        $('#advRisks').html('<li>Fallo al consultar /advisor</li>');
        $('#advPayload').text(JSON.stringify(payloadForBackend, null, 2));
      }
    });

  });

})();
</script>
@endsection
