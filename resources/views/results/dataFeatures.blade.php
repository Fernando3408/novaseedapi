<div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample6">

  @if(!empty($response))
    @foreach($response as $key => $record)
        <div class="card">
          <div class="card-header" id="headingOne{{$key}}">
            <div class="card-title" data-toggle="collapse" data-target="#collapseOne{{$key}}">
              <i class="flaticon-pie-chart-1"></i> Año {{$key}}
            </div>
          </div>
          <div id="collapseOne{{$key}}" class="collapse show" data-parent="#accordionExample6">
            <div class="card-body">
              <table class="table table-striped- table-bordered table-hover table-checkable datatables_p" id="table_detail_{{$key}}">
                <thead>
                  <tr>
                    <th>Valor</th>
                    <th>Ensayo</th>
                    <th>Localidad</th>
                    <th>Fecha</th>
                    <th>Reps</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($record as $r)
                      <tr>
                        <td><input type="readonly" value="{{$r['value']}}" id="{{$r['id']}}"></td>
                        <td><a href="/blocks/{{@$r->block->id}}" target="_blank">{{@$r->block->description}}</a></td>
                        <td><input type="readonly" value="{{$r['locality_id']}}" id="{{$r['locality_id']}}"></td>
                        <td><input type="readonly" value="{{$r['created_at']}}" id="{{$r['created_at']}}"></td>
                        <td><input type="readonly" value="{{$r['repeats_values']}}" id="{{$r['repeats_values']}}"></td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
    @endforeach
@endif
