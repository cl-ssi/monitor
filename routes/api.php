<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('webservices')->name('webservices.')->group(function () {
    Route::get('fonasa', 'WebserviceController@fonasa')->middleware('auth.basic')->name('fonasa');
});

Route::get('positives', 'SuspectCaseReportController@countPositives')->middleware('auth.basic');
Route::get('reporte_expertos', 'SuspectCaseReportController@reporteExpertos')->middleware('auth.basic');

//WS para modificación de resultado mediante integración Mirth Connect (EN DESARROLLO)
//Route::get('hl7_files', 'SuspectCaseReportController@getHl7Files')->middleware('auth.basic');

//API Monitor
Route::post('add_case', 'WebServiceController@addCase')->middleware('auth.basic');
