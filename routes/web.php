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

Route::get('register', 'Auth\RegisterController@showRegistrationForm')->middleware('guest')->name('register');
Route::post('register', 'Auth\RegisterController@register')->middleware('guest');

/*
GET|HEAD | login    | login     | App\Http\Controllers\Auth\LoginController@showLoginForm  | web,guest    |
POST     | login    |           | App\Http\Controllers\Auth\LoginController@login          | web,guest    |
POST     | logout   | logout    | App\Http\Controllers\Auth\LoginController@logout         | web          |
*/
Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('patients')->name('patients.')->middleware('auth')->group(function () {
    Route::get('/', 'PatientController@index')->name('index');
    Route::get('/create', 'PatientController@create')->name('create');
    Route::post('/', 'PatientController@store')->name('store');
    Route::get('/{patient}/edit', 'PatientController@edit')->name('edit');
    Route::put('/{patient}', 'PatientController@update')->name('update');
    Route::delete('/{patient}', 'PatientController@destroy')->name('destroy');
});


Route::prefix('lab')->name('lab.')->group(function () {
    Route::prefix('suspect_cases')->name('suspect_cases.')->group(function () {
        Route::get('/', 'SuspectCaseController@index')->name('index')->middleware('auth');
        Route::get('/create', 'SuspectCaseController@create')->name('create')->middleware('auth');
        Route::post('/', 'SuspectCaseController@store')->name('store')->middleware('auth');
        Route::get('/{suspect_case}/edit', 'SuspectCaseController@edit')->name('edit')->middleware('auth');
        Route::put('/{suspect_case}', 'SuspectCaseController@update')->name('update')->middleware('auth');
        Route::delete('/{suspect_case}', 'SuspectCaseController@destroy')->name('destroy')->middleware('auth');
        Route::get('/report','SuspectCaseController@report')->name('report');
    });
});
