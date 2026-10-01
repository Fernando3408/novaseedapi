# Documentación del módulo de fritura

## 1. Alcance

Este documento describe cómo se registran, consultan y calculan las características asociadas a fritura en los ensayos.

La documentación cubre:

- Características de fritura.
- Valores generales por año.
- Detalle mensual.
- Promedios y valores ponderados.
- Flujo actual de pantallas, rutas y tablas.
- Riesgos y puntos que deben definirse antes de crear una nueva API.

No se documenta ni implementa todavía una API nueva.

## 2. Identificación de las características de fritura

Actualmente el sistema identifica las características de fritura mediante los IDs:

```php
[48, 49, 50, 51]
```

Estos IDs se utilizan en:

- `app/Http/Controllers/ApiController.php`
- `app/Http/Controllers/VarityController.php`
- `resources/views/varities/searchHome.blade.php`
- `resources/views/results/fries/searching.blade.php`

El catálogo real de características se encuentra en la tabla `features`, representada por:

```text
app/Models/Feature.php
```

La entidad también dispone del campo `frying_test`, que permite marcar una característica como relacionada con fritura. Sin embargo, varias partes del sistema utilizan directamente los IDs `48`, `49`, `50` y `51`, en vez de consultar exclusivamente ese indicador.

## 3. Modelo general de datos

El flujo de información relacionado con un ensayo es:

```text
Ensayo (blocks)
  -> Asociación ensayo-variedad (blocksvarities)
    -> Variedad (varities)
      -> Característica (features)
        -> Valor general por año (characteristics)
        -> Detalle mensual de fritura (fries_characteristics)
```

Los resultados precalculados de fritura se mantienen adicionalmente en:

```text
fries_avg_characteristics
```

## 4. Valor general por año

### Tabla

```text
characteristics
```

### Modelo

```text
app/Models/Characteristic.php
```

### Campos relevantes

```text
id
value
feature_id
varity_id
year
block_id
locality_id
zonaltrial_id
repeats_values
```

El valor general se identifica conceptualmente mediante:

```text
variety_id + feature_id + year
```

El registro puede incluir además el ensayo (`block_id`) y la localidad (`locality_id`).

Los años utilizados por las pantallas son:

```text
0, 1, 2, 3 y 4
```

### Registro desde la pantalla

Los valores se muestran y editan desde:

```text
resources/views/results/getCaracteristicas.blade.php
resources/views/blocks/getCaracteristicas.blade.php
```

Cada característica puede tener un valor para cada año. Por ejemplo:

```text
variedad: 25
característica: 48
año: 0
valor: 62
```

Las características visibles dentro de un ensayo dependen de la configuración de `showfarms`, que relaciona ensayo, característica y tipo.

## 5. Detalle mensual de fritura

### Tabla

```text
fries_characteristics
```

### Modelo

```text
app/fries_characteristics.php
```

### Campos mensuales

```text
enero
febrero
marzo
abril
mayo
junio
julio
agosto
septiembre
octubre
noviembre
diciembre
```

### Campos de relación

```text
characteristic_id
varity_id
feature
year
block_id
locality_id
```

El detalle mensual representa una fila por combinación de variedad, característica y año. Los valores de los meses se almacenan como columnas de la misma fila, no como registros separados por mes.

Ejemplo conceptual:

```text
variety_id: 25
feature: 48
marzo: 61
abril: 62
mayo: 63
```

## 6. Guardado del detalle mensual

### Ruta actual

```text
POST/GET /fries/save/
```

La ruta está declarada como `Route::any` en `routes/web.php`.

### Método

```php
BlockController@saveDataFries()
```

El método busca primero un registro usando:

```text
varity_id
year
feature
```

Si lo encuentra, lo actualiza. Si no lo encuentra, crea uno nuevo.

Después guarda los doce valores mensuales y los datos de variedad, año y característica.

La pantalla que envía estos valores es:

```text
resources/views/varities/create.blade.php
resources/views/results/fries/index.blade.php
```

## 7. Consulta actual del detalle mensual

### Ruta

```text
GET /fries/getData/
```

### Método

```php
BlockController@getDataFries()
```

Para consultar el detalle se envían:

```text
varity
feature
```

Ejemplo:

```text
/fries/getData/?varity=25&feature=48
```

Sin parámetro `year`, el método carga todos los registros encontrados para la variedad y característica y devuelve la vista:

```text
resources/views/results/fries/detail_fries.blade.php
```

Con parámetro `year`, intenta devolver un solo registro en formato JSON. Este comportamiento debe considerarse legado, porque la respuesta no está estructurada como una API formal y el filtro usa nombres de columnas que no coinciden completamente con el modelo documentado.

## 8. Cálculo ponderado mensual

El cálculo se realiza en:

```text
app/Http/Controllers/BlockController.php
```

Los factores actuales son:

```text
marzo      = 0.071
abril      = 0.077
mayo       = 0.084
junio      = 0.090
julio      = 0.097
agosto     = 0.103
septiembre = 0.110
octubre    = 0.116
noviembre  = 0.123
```

Enero, febrero y diciembre no participan actualmente en el cálculo ponderado, aunque sí existen como columnas en la tabla.

Para cada mes incluido se calcula:

```text
valor_ponderado_mes = valor_mes * factor_mes
```

El total anual ponderado se calcula como:

```text
total_ponderado =
  marzo_ponderado
  + abril_ponderado
  + mayo_ponderado
  + junio_ponderado
  + julio_ponderado
  + agosto_ponderado
  + septiembre_ponderado
  + octubre_ponderado
  + noviembre_ponderado
```

El resultado se agrega en memoria como `total_ponderado`; no se observa que este valor se guarde como columna en `fries_characteristics`.

## 9. Promedios precalculados

El sistema utiliza la tabla:

```text
fries_avg_characteristics
```

Modelo:

```text
app/Fries_avg_characteristics.php
```

Esta tabla es utilizada por `ApiController@searchResults()` cuando la característica consultada es una de las características de fritura.

Los campos usados por el buscador son:

```text
varity_id
feature_id
PROMEDIO
ponderado
```

El buscador devuelve estos valores como:

```json
{
  "promedio": "...",
  "ponderado": "..."
}
```

La documentación del proyecto no identifica en el código revisado el proceso que genera o actualiza `fries_avg_characteristics`. Por ello, no debe asumirse todavía que `PROMEDIO` sea exactamente el promedio de los doce meses ni que `ponderado` sea recalculado en tiempo real.

## 10. Pantallas y rutas relacionadas

### Pantalla de resultados de fritura

```text
/results/fries/
```

Controlador:

```text
ResultsController@fries()
```

Vista:

```text
resources/views/results/fries/index.blade.php
```

### Consulta del detalle

```text
/fries/getData/
```

### Guardado

```text
/fries/save/
```

### Actualización o eliminación

```text
/fries/updateFries/
```

Método:

```text
BlockController@updateDataFries()
```

## 11. Observaciones técnicas

### Relación con el ensayo

Aunque el detalle mensual tiene los campos `block_id` y `locality_id`, el guardado actual no los utiliza porque las condiciones están comentadas. En consecuencia, el detalle se identifica actualmente principalmente por variedad, característica y año.

### Diferencias de nombres

En distintos puntos se utilizan ambos nombres:

```text
characteristic_id
feature
feature_id
```

Esto puede representar conceptos relacionados, pero no están tratados de forma uniforme en todos los métodos.

### Dependencia de IDs fijos

La identificación de fritura mediante `[48, 49, 50, 51]` está duplicada en varios archivos. Es necesario validar los nombres reales en `features` antes de construir una API basada únicamente en esos IDs.

### Tabla sin migración visible

No se encontró una migración de `fries_characteristics` ni de `fries_avg_characteristics` dentro de `database/migrations`. La estructura descrita se obtuvo del modelo y del uso que hacen los controladores. Debe validarse directamente contra la base de datos.

### Valores nulos y meses sin datos

El cálculo actual multiplica directamente cada columna mensual por su factor. Debe verificarse cómo se comporta la base de datos cuando un mes está vacío, ya que la lógica no documenta una normalización explícita de valores nulos.

## 12. Definiciones pendientes antes de la API

Antes de implementar la API se debe confirmar:

1. Si el promedio solicitado corresponde a `characteristics.value`, al promedio mensual o a `fries_avg_characteristics.PROMEDIO`.
2. Si la respuesta debe incluir datos separados por ensayo (`block_id`) y localidad (`locality_id`).
3. Si enero, febrero y diciembre deben seguir excluidos del ponderado.
4. Qué significa exactamente `year`: año de ensayo, año relativo `0..4` o año calendario.
5. Cómo se actualiza `fries_avg_characteristics`.
6. Si `feature` y `feature_id` representan siempre el mismo ID.
7. Si la API debe consultar datos históricos o recalcular los promedios en cada solicitud.

## 13. Valores que se ponderan

El ponderado no utiliza directamente `characteristics.value`. Utiliza los valores mensuales almacenados en `fries_characteristics`.

Para cada variedad, característica y año se toman actualmente:

```text
marzo, abril, mayo, junio, julio, agosto, septiembre, octubre y noviembre
```

Enero, febrero y diciembre existen como columnas y pueden editarse, pero no participan en el ponderado actual.

La fórmula aplicada es:

```text
valor_ponderado_mes = valor_mensual * factor_mensual
```

La fórmula completa es:

```text
total_ponderado =
  marzo * 0.071
  + abril * 0.077
  + mayo * 0.084
  + junio * 0.090
  + julio * 0.097
  + agosto * 0.103
  + septiembre * 0.110
  + octubre * 0.116
  + noviembre * 0.123
```

Los factores suman aproximadamente `0.981`. Por lo tanto, el resultado actual es una suma ponderada y no un promedio normalizado.

## 14. Factores mensuales

Los factores están definidos directamente en `BlockController@getDataFries()`:

| Mes | Factor | Participa |
|---|---:|---|
| Enero | No definido | No |
| Febrero | No definido | No |
| Marzo | 0.071 | Sí |
| Abril | 0.077 | Sí |
| Mayo | 0.084 | Sí |
| Junio | 0.090 | Sí |
| Julio | 0.097 | Sí |
| Agosto | 0.103 | Sí |
| Septiembre | 0.110 | Sí |
| Octubre | 0.116 | Sí |
| Noviembre | 0.123 | Sí |
| Diciembre | No definido | No |

No existe una tabla de configuración para estos factores en el código revisado. Son constantes escritas directamente en PHP.

## 15. Qué hace cada estructura de base de datos

No se encontraron sentencias `CREATE VIEW` ni definiciones de vistas SQL dentro del código versionado. Por lo tanto, `fries_avg_characteristics` debe verificarse directamente en MySQL para confirmar si es tabla o vista.

### `features`

Catálogo de características. Entrega el nombre, grupo, tipo, `frying_test` y configuración de repeticiones.

### `characteristics`

Guarda el valor general de una característica para una variedad y un año. Sus campos funcionales son `feature_id`, `varity_id`, `year`, `value`, `block_id` y `locality_id`.

### `fries_characteristics`

Guarda el detalle mensual de fritura. Tiene los doce meses como columnas y es la fuente utilizada para calcular `total_ponderado` en tiempo de consulta.

### `fries_avg_characteristics`

Se utiliza en las búsquedas y filtros de variedades de fritura. El código lee `varity_id`, `feature_id`, `PROMEDIO` y `ponderado`. El proceso que genera o actualiza estos valores no está en el código revisado.

### `blocks`

Representa el ensayo y contiene su descripción, año, líneas, localidad y posiciones.

### `blocksvarities`

Relaciona el ensayo con sus variedades y posiciones. Permite saber qué variedades pertenecen a un ensayo.

### `showfarms`

Define qué características se muestran para un ensayo y tipo de ensayo. `BlockController@getCaracteristicas()` consulta esta configuración antes de cargar los valores anuales.

## 16. Qué hace cada vista de la aplicación

### `resources/views/results/fries/index.blade.php`

Es la pantalla principal de resultados de fritura. Permite seleccionar una variedad y característica, solicitar el detalle mensual, actualizar registros y eliminarlos.

### `resources/views/results/fries/detail_fries.blade.php`

Muestra los registros agrupados por año. Presenta los valores de marzo a noviembre, el `total_ponderado`, botones de guardado/eliminación y un gráfico mensual.

El gráfico utiliza valores mensuales sin aplicar el factor. El controlador acumula cada mes y divide por un contador de registros cuyo valor ponderado es mayor que cero.

### `resources/views/results/getCaracteristicas.blade.php`

Muestra y permite editar los valores generales de las características para los años 0 a 4. En fritura utiliza el indicador `frying_test` de la característica.

### `resources/views/blocks/getCaracteristicas.blade.php`

Muestra las características habilitadas para un ensayo concreto considerando ensayo, tipo, localidad, variedad y año. También activa cálculos agrícolas adicionales.

## 17. Diferencia entre los resultados

| Resultado | Fuente | Obtención |
|---|---|---|
| Valor anual | `characteristics.value` | Valor guardado para variedad, característica y año |
| Total ponderado | `fries_characteristics` | Suma de marzo a noviembre multiplicados por factores |
| Promedio/ponderado de búsqueda | `fries_avg_characteristics` | Valor previamente almacenado, cuyo proceso de generación no está en el código |

Estos valores no deben considerarse equivalentes sin validar la base de datos y el proceso que genera `fries_avg_characteristics`.

## 18. Conclusión de la revisión

Actualmente se ponderan únicamente los valores mensuales de marzo a noviembre de `fries_characteristics`. Cada mes tiene un factor diferente y el resultado es una suma ponderada, no un promedio normalizado.

Los valores anuales de `characteristics` se registran por separado y no participan directamente en ese cálculo.

La estructura `fries_avg_characteristics` entrega valores precalculados para búsquedas, pero el proyecto no contiene la lógica que explica cómo fueron generados. Esa parte requiere revisión directa de la base de datos, procedimientos externos, scripts de carga o procesos históricos.

## 19. API implementada

Se agregó una API de solo lectura:

```text
GET /api/fries/{variety}/{feature}
```

Ejemplo:

```text
GET /api/fries/25/48
```

La implementación está en:

```text
app/Http/Controllers/ApiController.php
```

La ruta está declarada en:

```text
routes/api.php
```

La respuesta incluye:

- Identificación de la variedad.
- Identificación y nombre de la característica.
- Factores mensuales utilizados.
- Promedio compatible con Results en el índice principal (`value_avg`).
- Ponderado compatible con Results en el índice principal (`value_ponderate`).
- Valores anuales desde `characteristics`.
- Detalles mensuales desde `fries_characteristics`.
- Detalle integrado por año en `year_details`.
- `weighted_total` recalculado para cada año.
- `PROMEDIO` y `ponderado` desde `fries_avg_characteristics`, cuando existe el registro.

El cálculo de la API utiliza cero cuando un valor mensual está vacío y redondea `weighted_total` a tres decimales. La API no modifica datos.

`value_avg` utiliza `fries_avg_characteristics.PROMEDIO`, que es el valor que utiliza la pantalla Results. Si no existe ese registro precalculado, la API utiliza como respaldo el promedio aritmético de los valores numéricos disponibles en `annual_values`. `annual_average_raw` conserva este último cálculo para trazabilidad.

`value_ponderate` utiliza `fries_avg_characteristics.ponderado`, que es el ponderado mostrado por Results. Los valores `weighted_total` dentro de `monthly_details` son cálculos individuales por año y no reemplazan al ponderado principal.

`year_details` integra por cada año el valor anual, los doce meses y el ponderado calculado de ese año. Esto evita que el consumidor tenga que relacionar manualmente `annual_values` con `monthly_details`.
