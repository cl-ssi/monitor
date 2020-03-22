<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('patients')->name('patients.')->group(function () {
    Route::get('/', 'PatientController@index')->name('index');
    Route::get('/create', 'PatientController@create')->name('create');
    Route::post('/', 'PatientController@store')->name('store');
    Route::get('/{patient}/edit', 'PatientController@edit')->name('edit');
    Route::put('/{patient}', 'PatientController@update')->name('update');
    Route::delete('/{patient}', 'PatientController@destroy')->name('destroy');
});


Route::prefix('lab')->name('lab.')->group(function () {
    Route::prefix('suspect_cases')->name('suspect_cases.')->group(function () {
        Route::get('/', 'SuspectCaseController@index')->name('index');
        Route::get('/create', 'SuspectCaseController@create')->name('create');
        Route::post('/', 'SuspectCaseController@store')->name('store');
        Route::get('/{suspect_case}/edit', 'SuspectCaseController@edit')->name('edit');
        Route::put('/{suspect_case}', 'SuspectCaseController@update')->name('update');
        Route::delete('/{suspect_case}', 'SuspectCaseController@destroy')->name('destroy');
        Route::get('/report','SuspectCaseController@report')->name('report');
    });
});
