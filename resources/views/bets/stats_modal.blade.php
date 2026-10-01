<!-- resources/views/partials/_stats_modal.blade.php -->
<div class="modal fade" id="statsModal" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px!important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Estadísticas detalladas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        {{-- Últimos 5 partidos lado a lado --}}
        <div class="row mb-4">
          <div class="col-md-6">
            <h6>Últimos 5 partidos – <span id="teamAName"></span></h6>
            <table class="dttable table table-bordered">
              <thead>
                <tr>
                  <th>P1</th><th>T1</th>
                  <th>P2</th><th>T2</th>
                  <th>ht1</th><th>ht2</th><th>htt</th>
                  <th>ft1</th><th>ft2</th><th>ft_total</th><th>sp</th>
                </tr>
              </thead>
              <tbody id="last5A">
                {{-- JS insertará aquí 5 <tr> --}}
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <h6>Últimos 5 partidos – <span id="teamBName"></span></h6>
            <table class="dttable table table-bordered">
              <thead>
                <tr>
                  <th>P1</th><th>T1</th>
                  <th>P2</th><th>T2</th>
                  <th>ht1</th><th>ht2</th><th>htt</th>
                  <th>ft1</th><th>ft2</th><th>ft_total</th><th>sp</th>
                </tr>
              </thead>
              <tbody id="last5B">
                {{-- JS insertará aquí 5 <tr> --}}
              </tbody>
            </table>
          </div>
        </div>

        {{-- Tabla de indicadores --}}
        <div class="row">
          <div class="col">
            <table class="table table-sm table-bordered">
              <thead class="thead-light">
                <tr>
                  <th rowspan="2" class="text-center">Indicador</th>
                  <th colspan="2" class="text-center">Matches</th>
                  <th colspan="2" class="text-center">0 GOLES ST</th>
                  <th colspan="2" class="text-center">1 GOLES ST</th>
                  <th colspan="2" class="text-center">2 GOLES ST</th>
                  <th colspan="2" class="text-center">3 GOLES ST</th>
                  <th colspan="2" class="text-center">+4GOLES ST</th>
                  <th colspan="4" class="text-center">Market</th>
                </tr>
                <tr>
                  <th>Filtered</th>
                  <th>Global</th>
                  <th>Filtered</th>
                  <th>Global</th>
                  <th>Filtered</th>
                  <th>Global</th>
                  <th>Filtered</th>
                  <th>Global</th>
                  <th>Filtered</th>
                  <th>Global</th>
                  <th>Filtered</th>
                  <th>Global</th>
                  <th>OF</th>
                  <th>OG</th>
                  <th>UF</th>
                  <th>UG</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
