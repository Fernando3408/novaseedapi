@extends('layouts.app')
@section('content')
<style type="text/css">
    .guide-badge {
        display: inline-block;
        padding: 4px 10px;
        margin: 0 6px 6px 0;
        border-radius: 20px;
        background: #f4f6fb;
        border: 1px solid #dfe6f1;
        font-size: 12px;
        color: #495057;
    }

    .guide-note {
        background: #f8fafc;
        border-left: 4px solid #34bfa3;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .guide-table td,
    .guide-table th {
        vertical-align: top !important;
    }

    .guide-values {
        max-width: 420px;
        white-space: normal;
        line-height: 1.4;
    }
</style>

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-2"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Guia de Configuracion de Caracteristicas
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="guide-note">
            <strong>Base de esta guia:</strong> la vista documenta como el modulo <code>/results</code> agrega caracteristicas y como <code>searchHome</code> interpreta operadores, rangos y valores para cada tipo disponible.
        </div>

        <div class="guide-note">
            <strong>Reglas generales del buscador:</strong>
            <br>Las caracteristicas de tipo 1 no muestran un operador textual; el filtro se arma marcando uno o varios valores.
            <br>Las caracteristicas de tipo 2 trabajan solo con la decision Si o No.
            <br>Las caracteristicas de tipo 3 admiten <code>==</code>, <code>!=</code>, <code>&gt;</code>, <code>&lt;</code> y <code>[value1 ; value2]</code>.
        </div>

        @foreach($families as $family)
            <div class="m-section" style="margin-top: 30px;">
                <h4>
                    {{ $family['name'] }}
                    @if(!empty($family['secondary_name']) && $family['secondary_name'] !== $family['name'])
                        <small>/ {{ $family['secondary_name'] }}</small>
                    @endif
                </h4>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered guide-table">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="220">Caracteristica</th>
                            <th width="150">Tipo</th>
                            <th width="260">Como se ingresa en Results</th>
                            <th width="220">Operadores admitidos</th>
                            <th width="160">Configuracion</th>
                            <th width="180">Origen de promedio</th>
                            <th>Valores / Escala</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($family['features'] as $feature)
                            <tr>
                                <td>{{ $feature['id'] }}</td>
                                <td>
                                    <strong>{{ $feature['name'] }}</strong>
                                    @if(!empty($feature['secondary_name']) && $feature['secondary_name'] !== $feature['name'])
                                        <br><small>{{ $feature['secondary_name'] }}</small>
                                    @endif
                                </td>
                                <td>{{ $feature['type_label'] }}</td>
                                <td>{{ $feature['input_mode'] }}</td>
                                <td>
                                    @foreach($feature['operator_labels'] as $label)
                                        <span class="guide-badge">{{ $label }}</span>
                                    @endforeach
                                    <div style="margin-top: 8px;">
                                        <small>{{ $feature['operator_description'] }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong>Anios:</strong> {{ $feature['year_scope'] }}<br>
                                    <strong>Localidad:</strong> {{ $feature['locality_scope'] }}<br>
                                    <strong>Repeticiones:</strong> {{ $feature['repeat_mode'] }}<br>
                                    <strong>Fries:</strong> {{ $feature['frying_mode'] }}
                                </td>
                                <td><code>{{ $feature['source_table'] }}</code></td>
                                <td class="guide-values">
                                    {{ $feature['values_summary'] }}
                                    @if(!empty($feature['values']))
                                        <div style="margin-top: 10px;">
                                            @foreach($feature['values'] as $value)
                                                <span class="guide-badge">{{ $value }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
@endsection
