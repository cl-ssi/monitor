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

//ruta para capturar data enviada desde intranet.
Route::match(['get', 'post'],'endpoint/receiveDispatchC19','EndpointController@receiveDispatchC19')->name('endpoint.receiveDispatchC19');
Route::match(['get', 'post'],'endpoint/deleteDispatchC19','EndpointController@deleteDispatchC19')->name('endpoint.deleteDispatchC19');


Route::get('login', 'Auth\LoginController@showLoginForm')->middleware('guest')->name('login');
Route::post('login', 'Auth\LoginController@login')->middleware('guest');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->middleware('guest')->name('register');
//Route::post('register', 'Auth\RegisterController@register')->middleware('guest');

Route::prefix('users')->name('users.')->middleware('auth')->group(function () {
    Route::prefix('password')->name('password.')->group(function () {
        Route::get('/', 'UserController@showPasswordForm')->name('show_form');
        Route::put('/', 'UserController@updatePassword')->name('update');
    });
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

Route::resource('epp','EppController')->middleware('auth');

Route::prefix('lab')->name('lab.')->group(function () {
    Route::get('login/{access_token}','SuspectCaseController@login')->name('login');
    Route::get('results','SuspectCaseController@result')->name('result');
    Route::get('print/{suspect_case}','SuspectCaseController@print')->middleware('auth')->name('print');
    Route::prefix('suspect_cases')->name('suspect_cases.')->group(function () {
        Route::get('/hetg','SuspectCaseController@hetg')->name('hetg');
        Route::get('/unap','SuspectCaseController@unap')->name('unap');
        Route::get('/excelunap','SuspectCaseController@excelunap')->name('excelunap');
        //Route::get('stat', 'SuspectCaseController@stat')->name('stat');
        // Route::get('case_chart','SuspectCaseController@case_chart')->name('case_chart')->middleware('auth');
        Route::match(['get','post'],'case_chart','SuspectCaseController@case_chart')->middleware('auth')->name('case_chart');
        Route::match(['get','post'],'file_report','SuspectCaseController@file_report')->middleware('auth','can:File_report: viewer')->name('file_report');
        Route::get('download/{file}','SuspectCaseController@download')->name('download')->middleware('auth');
        Route::get('/','SuspectCaseController@index')->name('index')->middleware('auth','can:SuspectCase: list');
        Route::get('/create','SuspectCaseController@create')->name('create')->middleware('auth','can:SuspectCase: create');
        Route::post('/','SuspectCaseController@store')->name('store')->middleware('auth','can:SuspectCase: create');
        Route::get('/{suspect_case}/edit','SuspectCaseController@edit')->name('edit')->middleware('auth','can:SuspectCase: edit');
        Route::put('/{suspect_case}','SuspectCaseController@update')->name('update')->middleware('auth','can:SuspectCase: edit');
        Route::delete('/{suspect_case}','SuspectCaseController@destroy')->name('destroy')->middleware('auth','can:SuspectCase: delete');
        Route::get('/report','SuspectCaseController@report')->name('report')->middleware('auth','can:Report');
    });
});

Route::prefix('parameters')->as('parameters.')->middleware('auth')->group(function(){
    Route::get('/', 'Parameters\ParameterController@index')->name('index');
    Route::resource('permissions','Parameters\PermissionController');
});

Route::prefix('sanitary_residences')->name('sanitary_residences.')->middleware('auth')->group(function () {
    Route::prefix('residences')->name('residences.')->group(function () {
        Route::get('/create', 'ResidenceController@create')->name('create');
        Route::get('/', 'ResidenceController@index')->name('index');
        Route::post('/', 'ResidenceController@store')->name('store');
        // Route::get('/{residence}/edit', 'ResidenceController@edit')->name('edit');
        // Route::put('update/{residence}', 'ResidenceController@update')->name('update');
        // Route::delete('/{residence}', 'ResidenceController@destroy')->name('destroy');
    });

    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', 'RoomController@index')->name('index');
        Route::get('/create', 'RoomController@create')->name('create');
        Route::post('/', 'RoomController@store')->name('store');
        // Route::get('/{room}/edit', 'RoomController@edit')->name('edit');
        // Route::put('/{room}', 'RoomController@update')->name('update');
        // Route::delete('/{room}', 'RoomController@destroy')->name('destroy');
    });

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/excelall','BookingController@excelall')->name('excelall');
        Route::get('/excel/{booking}','BookingController@excel')->name('excel');
        Route::get('/', 'BookingController@index')->name('index');
        Route::get('/create', 'BookingController@create')->name('create');
        Route::get('/{booking}', 'BookingController@show')->name('show');
        Route::post('/', 'BookingController@store')->name('store');
        Route::get('/{booking}', 'BookingController@show')->name('show');
        // Route::get('/{booking}/edit', 'BookingController@edit')->name('edit');
        Route::put('/{booking}', 'BookingController@update')->name('update');
        // Route::delete('/{booking}', 'BookingController@destroy')->name('destroy');
    });


    Route::prefix('vital_signs')->name('vital_signs.')->group(function () {
        Route::get('/', 'VitalSignController@index')->name('index');
        Route::get('/create', 'VitalSignController@create')->name('create');
        Route::post('/', 'VitalSignController@store')->name('store');
        // Route::get('/{room}/edit', 'RoomController@edit')->name('edit');
        // Route::put('/{room}', 'RoomController@update')->name('update');
        // Route::delete('/{room}', 'RoomController@destroy')->name('destroy');
    });


    Route::prefix('evolutions')->name('evolutions.')->group(function () {
        Route::get('/', 'EvolutionController@index')->name('index');
        Route::get('/create', 'EvolutionController@create')->name('create');
        Route::post('/', 'EvolutionController@store')->name('store');
        // Route::get('/{room}/edit', 'RoomController@edit')->name('edit');
        // Route::put('/{room}', 'RoomController@update')->name('update');
        // Route::delete('/{room}', 'RoomController@destroy')->name('destroy');
    });


});
