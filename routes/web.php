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

//Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->middleware('guest')->name('login');
Route::post('login', 'Auth\LoginController@login')->middleware('guest');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->middleware('guest')->name('register');
//Route::post('register', 'Auth\RegisterController@register')->middleware('guest');

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', 'UserController@index')->name('index')->middleware('can:Admin');
    Route::get('/create', 'UserController@create')->name('create')->middleware('can:Admin');
    Route::post('/', 'UserController@store')->name('store')->middleware('can:Admin');
    Route::get('/{user}/edit', 'UserController@edit')->name('edit')->middleware('can:Admin');
    Route::put('/{user}', 'UserController@update')->name('update')->middleware('can:Admin');
    Route::delete('/{user}', 'UserController@destroy')->name('destroy')->middleware('can:Admin');
});

/*
GET|HEAD | login    | login     | App\Http\Controllers\Auth\LoginController@showLoginForm  | web,guest    |
POST     | login    |           | App\Http\Controllers\Auth\LoginController@login          | web,guest    |
POST     | logout   | logout    | App\Http\Controllers\Auth\LoginController@logout         | web          |
*/
Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('patients')->name('patients.')->middleware('auth')->group(function () {
    Route::get('get/{rut?}','PatientController@getPatient')->name('get')->middleware('auth');
    Route::get('georeferencing','PatientController@georeferencing')->name('georeferencing')->middleware('can:Patient: georeferencing');
    Route::get('/', 'PatientController@index')->name('index')->middleware('can:Patient: list');
    Route::get('/create', 'PatientController@create')->name('create')->middleware('can:Patient: create');
    Route::post('/', 'PatientController@store')->name('store')->middleware('can:Patient: create');
    Route::get('/{patient}/edit', 'PatientController@edit')->name('edit')->middleware('can:Patient: edit');
    Route::put('/{patient}', 'PatientController@update')->name('update')->middleware('can:Patient: edit');
    Route::delete('/{patient}', 'PatientController@destroy')->name('destroy')->middleware('can:Patient: delete');
});


Route::prefix('lab')->name('lab.')->group(function () {
    Route::get('login/{access_token?}', 'SuspectCaseController@login')->name('login');
    Route::get('results', 'SuspectCaseController@result')->name('result');
    Route::prefix('suspect_cases')->name('suspect_cases.')->group(function () {
        Route::get('download/{file}',  'SuspectCaseController@download')->name('download')->middleware('auth');
        Route::get('/', 'SuspectCaseController@index')->name('index')->middleware('auth','can:SuspectCase: list');
        Route::get('/create', 'SuspectCaseController@create')->name('create')->middleware('auth','can:SuspectCase: create');
        Route::post('/', 'SuspectCaseController@store')->name('store')->middleware('auth','can:SuspectCase: create');
        Route::get('/{suspect_case}/edit', 'SuspectCaseController@edit')->name('edit')->middleware('auth','can:SuspectCase: edit');
        Route::put('/{suspect_case}', 'SuspectCaseController@update')->name('update')->middleware('auth','can:SuspectCase: edit');
        Route::delete('/{suspect_case}', 'SuspectCaseController@destroy')->name('destroy')->middleware('auth','can:SuspectCase: delete');
        Route::get('/report','SuspectCaseController@report')->name('report')->middleware('auth','can:Report');
    });
});

Route::prefix('parameters')->as('parameters.')->middleware('auth')->group(function(){
    Route::get('/', 'Parameters\ParameterController@index')->name('index');
    Route::resource('permissions','Parameters\PermissionController');
});
