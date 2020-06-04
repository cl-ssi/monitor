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

Route::get('test/fonasa', 'TestController@fonasa');

Route::prefix('webservices')->name('webservices.')->group(function () {
    Route::get('fonasa', 'WebserviceController@fonasa')->middleware('auth')->name('fonasa');
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
    Route::get('positives', 'PatientController@positives')->name('positives')->middleware('can:Patient: list');
    Route::get('/create', 'PatientController@create')->name('create')->middleware('can:Patient: create');
    Route::post('/', 'PatientController@store')->name('store')->middleware('can:Patient: create');
    Route::get('/{patient}/edit', 'PatientController@edit')->name('edit')->middleware('can:Patient: edit');
    Route::put('/{patient}', 'PatientController@update')->name('update')->middleware('can:Patient: edit');
    Route::delete('/{patient}', 'PatientController@destroy')->name('destroy')->middleware('can:Patient: delete');
    Route::get('/export', 'PatientController@export')->name('export');
    Route::get('/exportPositives', 'PatientController@exportPositives')->name('exportPositives');
});

Route::resource('epp','EppController')->middleware('auth');


Route::prefix('help_basket')->name('help_basket.')->middleware('auth')->group(function () {
    Route::get('/', 'HelpBasketController@index')->name('index');
    Route::get('/create', 'HelpBasketController@create')->name('create');
    Route::get('/georeferencing', 'HelpBasketController@georeferencing')->name('georeferencing');
    Route::post('/store', 'HelpBasketController@store')->name('store');
    Route::get('/{helpBasket}/edit', 'HelpBasketController@edit')->name('edit');
    Route::put('{helpBasket}', 'HelpBasketController@update')->name('update');
    Route::delete('/{helpBasket}', 'HelpBasketController@Destroy')->name('destroy');
    Route::get('/download/{storage}/{file?}', 'HelpBasketController@download')->name('download');
    Route::get('/excel','HelpBasketController@excel')->name('excel');

});



Route::prefix('lab')->name('lab.')->group(function () {
    //Route::get('/', 'LaboratoryController@index')->name('index');
    //Route::get('/create', 'LaboratoryController@create')->name('create');
    //Route::post('/store', 'LaboratoryController@store')->name('store');
    //Route::get('/{laboratory}/edit', 'LaboratoryController@edit')->name('edit');
    //Route::put('update/{laboratory}', 'LaboratoryController@update')->name('update');
    Route::get('login/{access_token}','SuspectCaseController@login')->name('login');
    Route::get('results','SuspectCaseController@result')->name('result');
    Route::get('print/{suspect_case}','SuspectCaseController@print')->middleware('auth')->name('print');
    Route::prefix('exams')->name('exams.')->middleware('auth')->group(function () {
        Route::prefix('covid19')->name('covid19.')->group(function () {
            Route::get('/', 'Covid19Controller@index')->name('index');
            Route::get('/create', 'Covid19Controller@create')->name('create');
            Route::post('/', 'Covid19Controller@store')->name('store');
            Route::get('/{covid19}/edit', 'Covid19Controller@edit')->name('edit');
            Route::put('/{covid19}', 'Covid19Controller@update')->name('update');
            Route::put('/{covid19}/reception', 'Covid19Controller@reception')->name('reception');
            Route::put('/{covid19}/addresult', 'Covid19Controller@addresult')->name('addresult');
            Route::delete('/{covid19}', 'Covid19Controller@destroy')->name('destroy');
            Route::get('/download/{storage}/{file?}', 'Covid19Controller@download')->name('download');
            Route::get('/export', 'Covid19Controller@export')->name('export');
        });
    });
    Route::prefix('suspect_cases')->name('suspect_cases.')->group(function () {
        // Route::get('/hetg','SuspectCaseController@hetg')->name('hetg')->middleware('auth');
        // Route::get('/unap','SuspectCaseController@unap')->name('unap')->middleware('auth');
        // Route::get('/bioclinic','SuspectCaseController@bioclinic')->name('bioclinic')->middleware('auth');

        Route::get('reception_inbox','SuspectCaseController@reception_inbox')->name('reception_inbox')->middleware('auth','can:SuspectCase: reception');
        Route::post('reception/{suspectCase}','SuspectCaseController@reception')->name('reception')->middleware('auth','can:SuspectCase: reception');

        Route::post('/search_id','SuspectCaseController@search_id')->name('search_id')->middleware('auth');
        //Route::get('stat', 'SuspectCaseController@stat')->name('stat');

        Route::get('download/{file}','SuspectCaseController@download')->name('download')->middleware('auth');
        Route::get('file/{file}','SuspectCaseController@fileDelete')->name('fileDelete')->middleware('auth','can:SuspectCase: file delete');

        Route::get('/index/{laboratory?}','SuspectCaseController@index')->name('index')->middleware('auth','can:SuspectCase: list');

        Route::get('/ownIndex/{laboratory?}','SuspectCaseController@ownIndex')->name('ownIndex')->middleware('auth','can:SuspectCase: own');

        Route::get('/exportSuspectCases/{lab}','SuspectCaseController@exportExcel')->name('export')->middleware('auth');

        Route::get('/create','SuspectCaseController@create')->name('create')->middleware('auth','can:SuspectCase: create');
        Route::post('/','SuspectCaseController@store')->name('store')->middleware('auth','can:SuspectCase: create');
        Route::get('/admission','SuspectCaseController@admission')->name('admission')->middleware('auth','can:SuspectCase: admission');
        Route::post('/admission','SuspectCaseController@storeAdmission')->name('store_admission')->middleware('auth','can:SuspectCase: admission');
        Route::get('/{suspect_case}/edit','SuspectCaseController@edit')->name('edit')->middleware('auth','can:SuspectCase: edit');
        Route::put('/{suspect_case}','SuspectCaseController@update')->name('update')->middleware('auth','can:SuspectCase: edit');
        Route::delete('/{suspect_case}','SuspectCaseController@destroy')->name('destroy')->middleware('auth','can:SuspectCase: delete');
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/positives','SuspectCaseReportController@positives')->name('positives')->middleware('auth','can:Report: positives');
            Route::get('case_tracing','SuspectCaseReportController@case_tracing')->name('case_tracing')->middleware('auth','can:Patient: tracing');
            Route::get('/gestants','SuspectCaseReportController@gestants')->name('gestants')->middleware('auth','can:Report: positives');
            // Route::get('case_chart','SuspectCaseController@case_chart')->name('case_chart')->middleware('auth');
            Route::match(['get','post'],'case_chart','SuspectCaseReportController@case_chart')->middleware('auth')->name('case_chart');
            Route::match(['get','post'],'exams_with_result','SuspectCaseReportController@exams_with_result')->middleware('auth','can:Report: exams with result')->name('exams_with_result');
            Route::get('/minsal/{laboratory}','SuspectCaseReportController@report_minsal')->name('minsal')->middleware('auth');
            Route::get('/seremi/{laboratory}','SuspectCaseReportController@report_seremi')->name('seremi')->middleware('auth');
        });
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/','SuspectCaseReportController@positives')->name('index')->middleware('auth','can:Report: other');
            Route::get('historical_report','SuspectCaseController@historical_report')->name('historical_report')->middleware('auth','can:Report: historical');
            Route::get('/minsal-export/{laboratory}','SuspectCaseController@exportMinsalExcel')->name('exportMinsal')->middleware('auth');
            Route::get('/seremi-export/{laboratory}','SuspectCaseController@exportSeremiExcel')->name('exportSeremi')->middleware('auth');
            Route::get('/ws_minsal/{laboratory}','SuspectCaseReportController@ws_minsal')->name('ws_minsal')->middleware('auth');
            Route::get('diary_lab_report','SuspectCaseController@diary_lab_report')->name('diary_lab_report')->middleware('auth');
            Route::get('estadistico_diario_covid19','SuspectCaseController@estadistico_diario_covid19')->name('estadistico_diario_covid19')->middleware('auth');
        });
    });
    Route::prefix('sample_origins')->name('sample_origins.')->group(function () {
        Route::get('/', 'SampleOriginController@index')->name('index');
        Route::get('/create', 'SampleOriginController@create')->name('create');
        Route::post('/', 'SampleOriginController@store')->name('store');
        Route::get('/{sampleOrigin}/edit', 'SampleOriginController@edit')->name('edit');
        Route::put('/{sampleOrigin}', 'SampleOriginController@update')->name('update');
        //Route::delete('/{sample_origins}', 'SampleOriginController@destroy')->name('destroy');
    });
});

Route::prefix('parameters')->as('parameters.')->middleware('auth')->group(function(){
    Route::get('/', 'Parameters\ParameterController@index')->name('index');
    Route::resource('permissions','Parameters\PermissionController');
    Route::get('/ventilators/edit', 'VentilatorController@edit')->name('ventilators.edit');
    Route::put('/ventilators', 'VentilatorController@update')->name('ventilators.update');
    Route::get('/lab', 'LaboratoryController@index')->name('lab');
    Route::get('/lab/create', 'LaboratoryController@create')->name('lab.create');
    Route::post('/lab/store', 'LaboratoryController@store')->name('lab.store');
    Route::get('/lab/{laboratory}/edit', 'LaboratoryController@edit')->name('lab.edit');
    Route::put('/lab/update/{laboratory}', 'LaboratoryController@update')->name('lab.update');
});

Route::prefix('sanitary_residences')->name('sanitary_residences.')->middleware('auth')->group(function () {
    Route::get('/', 'ResidenceController@home')->name('home');
    Route::get('/users', 'ResidenceController@users')->name('users');
    Route::post('/users', 'ResidenceController@usersStore')->name('users.store');
    //Route::get('/{user}/edit', 'ResidenceController@usersEdit')->name('users.edit');
    Route::delete('/{residenceUser}', 'ResidenceController@usersDestroy')->name('users.destroy');
    Route::get('/report', 'ResidenceController@report')->name('report');


    Route::prefix('residences')->name('residences.')->group(function () {
        Route::get('/create', 'ResidenceController@create')->name('create');
        Route::get('/', 'ResidenceController@index')->name('index');
        Route::post('/', 'ResidenceController@store')->name('store');
        Route::get('/{residence}/edit', 'ResidenceController@edit')->name('edit');
        Route::put('update/{residence}', 'ResidenceController@update')->name('update');
        Route::delete('/{residence}', 'ResidenceController@destroy')->name('destroy');

    });

    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', 'RoomController@index')->name('index');
        Route::get('/create', 'RoomController@create')->name('create');
        Route::post('/', 'RoomController@store')->name('store');
        Route::get('/{room}/edit', 'RoomController@edit')->name('edit');
        Route::put('/{room}', 'RoomController@update')->name('update');
        // Route::delete('/{room}', 'RoomController@destroy')->name('destroy');
    });

    Route::prefix('bookings')->name('bookings.')->group(function () {

        Route::get('/excelall','BookingController@excelall')->name('excelall');
        Route::get('/excelvitalsign','BookingController@excelvitalsign')->name('excelvitalsign');
        Route::get('/excel/{booking}','BookingController@excel')->name('excel');
        Route::delete('/{booking}', 'BookingController@destroy')->name('destroy');
        Route::get('/residence/{residence}', 'BookingController@index')->name('index');
        Route::get('/create', 'BookingController@create')->name('create');
        Route::get('/{booking}', 'BookingController@show')->name('show');
        Route::post('/', 'BookingController@store')->name('store');
        Route::get('/{booking}', 'BookingController@show')->name('show');
        Route::get('/{booking}/release', 'BookingController@showRelease')->name('showrelease');
        // Route::get('/{booking}/edit', 'BookingController@edit')->name('edit');
        Route::put('/{booking}', 'BookingController@update')->name('update');
        //Route::delete('/destroy/{id}', 'BookingController@destroy')->name('destroy');
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

    Route::prefix('indications')->name('indications.')->group(function () {
        Route::get('/', 'IndicationController@index')->name('index');
        Route::get('/create', 'IndicationController@create')->name('create');
        Route::post('/', 'IndicationController@store')->name('store');
        // Route::get('/{room}/edit', 'RoomController@edit')->name('edit');
        // Route::put('/{room}', 'RoomController@update')->name('update');
        // Route::delete('/{room}', 'RoomController@destroy')->name('destroy');
    });


});
