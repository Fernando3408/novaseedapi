<?php

namespace App\Support;

use App\Models\Typefeature;

class FeatureGuideBuilder
{
    public function build()
    {
        $typefeatures = Typefeature::with([
            'features' => function ($query) {
                $query->orderBy('name', 'asc');
            },
            'features.values' => function ($query) {
                $query->orderBy('id', 'asc');
            },
        ])->orderBy('name', 'asc')->get();

        return $typefeatures->map(function ($typefeature) {
            return [
                'id' => $typefeature->id,
                'name' => $typefeature->name,
                'secondary_name' => isset($typefeature->names) ? $typefeature->names : null,
                'features' => $typefeature->features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'name' => $feature->name,
                        'secondary_name' => isset($feature->namees) ? $feature->namees : null,
                        'type' => (int) $feature->type,
                        'type_label' => $this->getTypeLabel($feature->type),
                        'input_mode' => $this->getInputMode($feature),
                        'operator_labels' => $this->getOperatorLabels($feature),
                        'operator_description' => $this->getOperatorDescription($feature),
                        'year_scope' => (int) $feature->year === 0 ? 'Todos los años' : 'Año '.$feature->year,
                        'locality_scope' => (int) $feature->localities_viewer === 0 ? 'Comun a todas las localidades' : 'Depende de la localidad seleccionada',
                        'repeat_mode' => (int) $feature->repeat_do === 1 ? 'Con repeticiones' : 'Sin repeticiones',
                        'frying_mode' => (int) $feature->frying_test === 1 ? 'Si' : 'No',
                        'localities_viewer' => (int) $feature->localities_viewer,
                        'repeat_do' => (int) $feature->repeat_do,
                        'frying_test' => (int) $feature->frying_test,
                        'source_table' => in_array((int) $feature->id, [48, 49, 50, 51]) ? 'Fries_avg_characteristics' : 'Avg_characteristic',
                        'values_summary' => $this->getValuesSummary($feature),
                        'values' => $this->getValuesList($feature),
                    ];
                })->values(),
            ];
        })->values();
    }

    protected function getTypeLabel($type)
    {
        if ((int) $type === 1) {
            return 'Escala / calificacion';
        }

        if ((int) $type === 2) {
            return 'Binaria Si/No';
        }

        if ((int) $type === 3) {
            return 'Valor libre con operador';
        }

        return 'No definido';
    }

    protected function getInputMode($feature)
    {
        if ((int) $feature->type === 2) {
            return 'Dos opciones fijas: Si o No.';
        }

        if ((int) $feature->type === 3) {
            return 'Selector de operador mas caja de texto para ingresar un valor.';
        }

        $firstValue = $feature->values->first();

        if (!$firstValue) {
            return 'Seleccion por escala, pero no tiene valores auxiliares configurados.';
        }

        if (!is_numeric($firstValue->value)) {
            return 'Seleccion multiple de valores discretos definidos en la tabla de valores.';
        }

        if (in_array((int) $feature->id, [71, 80, 125])) {
            return 'Seleccion multiple en escala porcentual de 0 a 100 con saltos de 10.';
        }

        if ((int) $feature->id === 46 || (float) $firstValue->value < 3) {
            return 'Seleccion multiple con 0 mas los valores configurados manualmente.';
        }

        return 'Seleccion multiple en escala estandar 0, 3, 4, 5, 6, 7, 8 y 9.';
    }

    protected function getOperatorLabels($feature)
    {
        if ((int) $feature->type === 3) {
            return ['==', '!=', '>', '<', '[value1 ; value2]'];
        }

        if ((int) $feature->type === 2) {
            return ['Si', 'No'];
        }

        return ['Seleccion directa', 'Rango por seleccion'];
    }

    protected function getOperatorDescription($feature)
    {
        if ((int) $feature->type === 3) {
            return 'Admite igualdad, diferencia, mayor que, menor que y rango manual usando el operador [value1 ; value2].';
        }

        if ((int) $feature->type === 2) {
            return 'No usa comparador textual. La condicion queda definida por la opcion Si o No.';
        }

        return 'No usa dropdown de operador. El filtro se construye marcando valores; con multiples selecciones el buscador toma el minimo y maximo seleccionados como borde del filtro.';
    }

    protected function getValuesSummary($feature)
    {
        if ((int) $feature->type === 2) {
            return 'Valores fijos del sistema: Si / No.';
        }

        if ((int) $feature->type === 3) {
            return 'Valor ingresado manualmente por el usuario.';
        }

        if ($feature->values->isEmpty()) {
            return 'Sin valores auxiliares configurados.';
        }

        return $feature->values->map(function ($value) {
            $label = trim((string) $value->value);
            $name = trim((string) $value->name);

            return $name !== '' ? $label.' - '.$name : $label;
        })->implode(' | ');
    }

    protected function getValuesList($feature)
    {
        if ((int) $feature->type === 2) {
            return ['Si', 'No'];
        }

        if ((int) $feature->type === 3) {
            return [];
        }

        return $feature->values->map(function ($value) {
            $label = trim((string) $value->value);
            $name = trim((string) $value->name);

            return $name !== '' ? $label.' - '.$name : $label;
        })->values()->all();
    }
}
