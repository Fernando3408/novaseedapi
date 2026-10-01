<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/api', function (Request $request) {
    return $request->user();
});
*/


Route::get('/export', 'ApiController@export');

Route::get('/agents/markets', 'AgentDataController@markets');
Route::get('/agents/standards', 'AgentDataController@standards');
Route::get('/agents/varieties', 'AgentDataController@varieties');
Route::get('/agents/avg-characteristics', 'AgentDataController@avgCharacteristics');
Route::get('/agents/results-summary', 'AgentDataController@resultsSummary');
Route::get('/agents/relative-yields', 'AgentDataController@relativeYields');
Route::get('/agents/greenhouse', 'AgentDataController@greenhouse');
Route::get('/agents/context/{agent}', 'AgentDataController@context');

Route::get('/fries/{variety}/{feature}', 'ApiController@fryingDetail');


Route::post('/potato-vision/devices/register', 'PotatoVisionController@registerDevice');
Route::group(array('prefix' => 'potato-vision', 'middleware' => 'potato.device'), function () {
    Route::post('/sessions', 'PotatoVisionController@startSession');
    Route::post('/sessions/{session}/finish', 'PotatoVisionController@finishSession');
    Route::post('/detections/batch', 'PotatoVisionController@storeBatch');
    Route::get('/sessions/{session}/metrics', 'PotatoVisionController@metrics');
});
