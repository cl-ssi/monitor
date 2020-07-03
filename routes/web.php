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
Route::get('/cuarentena', 'TracingController@quarantineCheck')->name('quarantineCheck')->middleware('auth');
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
        Route::get('/{user}/restore', 'UserController@passwordRestore')->name('restore');
        Route::put('/{user}', 'UserController@passwordStore')->name('store');
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
    Route::get('/in_residence', 'PatientController@inResidence')->name('in_residence');

    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/create/{search}/{id}', 'ContactPatientController@create')->name('create')->middleware('auth');
        Route::post('/', 'ContactPatientController@store')->name('store')->middleware('auth');
        Route::get('/{contact_patient}/edit', 'ContactPatientController@edit')->name('edit')->middleware('auth');
        Route::put('/{contact_patient}', 'ContactPatientController@update')->name('update')->middleware('auth');
        Route::get('delete/{contact_patient}','ContactPatientController@destroy')->name('destroy')->middleware('auth');;
    });

    Route::prefix('tracings')->name('tracings.')->middleware('auth')->group(function () {
        Route::get('/communes', 'TracingController@indexByCommune')->name('communes');
        Route::get('/establishments', 'TracingController@indexByEstablishment')->name('establishments');
        Route::get('/completed', 'TracingController@tracingCompleted')->name('completed');
        Route::get('/withouttracing', 'TracingController@withoutTracing')->name('withouttracing');


        Route::get('/create', 'TracingController@create')->name('create');
        Route::post('/', 'TracingController@store')->name('store');
        Route::get('/{tracing}/edit', 'TracingController@edit')->name('edit');
        Route::put('/{tracing}', 'TracingController@update')->name('update');
        Route::delete('/{tracing}', 'TracingController@destroy')->name('destroy');

        Route::get('/migrate', 'TracingController@migrate')->name('migrate');
        Route::prefix('events')->name('events.')->group(function () {
            Route::post('/', 'EventController@store')->name('store');
            Route::put('/{event}', 'EventController@update')->name('update');
            Route::delete('/{event}', 'EventController@destroy')->name('destroy');
        });

        // Route::prefix('requests')->name('requests.')->group(function () {
        //     Route::post('/', 'TracingRequestController@store')->name('store');
        //     Route::put('/{request}', 'TracingRequestController@update')->name('update');
        //     Route::delete('/{request}', 'TracingRequestController@destroy')->name('destroy');
        // });

        Route::prefix('requests')->name('requests.')->group(function () {
            Route::post('/', 'TracingRequestController@store')->name('store');
            Route::delete('/{request}', 'TracingRequestController@destroy')->name('destroy');

            Route::get('/social_index', 'TracingRequestController@social_index')->name('social_index');
            Route::get('/{tracing_request}/request_complete', 'TracingRequestController@request_complete')->name('request_complete');
            Route::put('/{tracing_request}', 'TracingRequestController@request_complete_update')->name('request_complete_update');
        });
    });
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
    Route::post('printpost/{suspect_case}','SuspectCaseController@printpost')->name('printpost');
    //Route::get('print/{suspect_case}','SuspectCaseController@print')->name('print');
    Route::prefix('exams')->name('exams.')->middleware('auth')->group(function () {
        Route::prefix('covid19')->name('covid19.')->group(function () {
            Route::get('/', 'SARSCoV2ExternalController@index')->name('index');
            Route::get('/create', 'SARSCoV2ExternalController@create')->name('create');
            Route::post('/', 'SARSCoV2ExternalController@store')->name('store');
            Route::get('/{covid19}/edit', 'SARSCoV2ExternalController@edit')->name('edit');
            Route::put('/{covid19}', 'SARSCoV2ExternalController@update')->name('update');
            Route::put('/{covid19}/reception', 'SARSCoV2ExternalController@reception')->name('reception');
            Route::put('/{covid19}/addresult', 'SARSCoV2ExternalController@addresult')->name('addresult');
            Route::delete('/{covid19}', 'SARSCoV2ExternalController@destroy')->name('destroy');
            Route::get('/download/{storage}/{file?}', 'SARSCoV2ExternalController@download')->name('download');
            Route::get('/export', 'SARSCoV2ExternalController@export')->name('export');
        });
    });
    Route::prefix('suspect_cases')->name('suspect_cases.')->group(function () {

        Route::get('reception_inbox','SuspectCaseController@reception_inbox')->name('reception_inbox')->middleware('auth','can:SuspectCase: reception');
        Route::post('reception/{suspect_case}','SuspectCaseController@reception')->name('reception')->middleware('auth','can:SuspectCase: reception');

        Route::post('/search_id','SuspectCaseController@search_id')->name('search_id')->middleware('auth');
        //Route::get('stat', 'SuspectCaseController@stat')->name('stat');

        Route::get('download/{file}','SuspectCaseController@download')->name('download')->middleware('auth');
        //Route::get('download/{file}','SuspectCaseController@download')->name('download');
        Route::get('file/{file}','SuspectCaseController@fileDelete')->name('fileDelete')->middleware('auth','can:SuspectCase: file delete');

        Route::get('/index/{laboratory?}','SuspectCaseController@index')->name('index')->middleware('auth','can:SuspectCase: list');

        Route::get('/ownIndex/{laboratory?}','SuspectCaseController@ownIndex')->name('ownIndex')->middleware('auth','can:SuspectCase: own');

        Route::get('/exportSuspectCases/{lab}','SuspectCaseController@exportExcel')->name('export')->middleware('auth');

//        pruebas
        Route::get('/exportAllCasesCsv','SuspectCaseController@exportAllCasesCsv')->name('exportAllCasesCsv')->middleware('auth');

        Route::get('/create','SuspectCaseController@create')->name('create')->middleware('auth','can:SuspectCase: create');
        Route::post('/','SuspectCaseController@store')->name('store')->middleware('auth','can:SuspectCase: create');
        Route::get('/admission','SuspectCaseController@admission')->name('admission')->middleware('auth','can:SuspectCase: admission');
        Route::post('/admission','SuspectCaseController@storeAdmission')->name('store_admission')->middleware('auth','can:SuspectCase: admission');
        Route::get('/{suspect_case}/edit','SuspectCaseController@edit')->name('edit')->middleware('auth','can:SuspectCase: edit');
        Route::put('/{suspect_case}','SuspectCaseController@update')->name('update')->middleware('auth','can:SuspectCase: edit');
        Route::delete('/{suspect_case}','SuspectCaseController@destroy')->name('destroy')->middleware('auth','can:SuspectCase: delete');
        Route::get('/{suspect_case}/notificationForm','SuspectCaseController@notificationForm')->name('notificationForm')->middleware('auth','can:SuspectCase: admission');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/positives','SuspectCaseReportController@positives')->name('positives')->middleware('auth','can:Report: positives');
            Route::get('case_tracing','SuspectCaseReportController@case_tracing')->name('case_tracing')->middleware('auth','can:Patient: tracing');
            Route::get('tracing_minsal','SuspectCaseReportController@tracing_minsal')->name('tracing_minsal')->middleware('auth','can:Patient: tracing');
            Route::get('case_tracing/export', 'SuspectCaseReportController@case_tracing_export')->name('case_tracing.export');
            Route::get('/gestants','SuspectCaseReportController@gestants')->name('gestants')->middleware('auth','can:Report: gestants');
            // Route::get('case_chart','SuspectCaseController@case_chart')->name('case_chart')->middleware('auth');
            Route::match(['get','post'],'case_chart','SuspectCaseReportController@case_chart')->middleware('auth')->name('case_chart');
            Route::match(['get','post'],'exams_with_result','SuspectCaseReportController@exams_with_result')->middleware('auth','can:Report: exams with result')->name('exams_with_result');
            Route::get('/minsal/{laboratory}','SuspectCaseReportController@report_minsal')->name('minsal')->middleware('auth');
            // Route::get('/minsal_ws','SuspectCaseReportController@report_minsal_ws')->name('minsal_ws')->middleware('auth');
            Route::match(['get','post'],'/minsal_ws','SuspectCaseReportController@report_minsal_ws')->name('minsal_ws');
            Route::get('/seremi/{laboratory}','SuspectCaseReportController@report_seremi')->name('seremi')->middleware('auth');
            Route::get('/positivesByDateRange','SuspectCaseReportController@positivesByDateRange')->name('positivesByDateRange')->middleware('auth');
            Route::get('/positives_own','SuspectCaseReportController@positivesOwn')->name('positives_own')->middleware('auth');
            Route::get('hospitalized','SuspectCaseReportController@hospitalized')->name('hospitalized')->middleware('auth','can:Report: hospitalized');
            Route::get('deceased','SuspectCaseReportController@deceased')->name('deceased')->middleware('auth','can:Report: deceased');
            Route::get('requires_licence','SuspectCaseReportController@requires_licence')->name('requires_licence')->middleware('auth', 'can:Report: requires licence');
        });
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/','SuspectCaseReportController@positives')->name('index')->middleware('auth','can:Report: other');
            Route::get('historical_report','SuspectCaseController@historical_report')->name('historical_report')->middleware('auth','can:Report: historical');
            Route::get('/minsal-export/{laboratory}','SuspectCaseController@exportMinsalExcel')->name('exportMinsal')->middleware('auth');
            Route::get('/seremi-export/{laboratory}','SuspectCaseController@exportSeremiExcel')->name('exportSeremi')->middleware('auth');
            Route::get('/ws_minsal','SuspectCaseReportController@ws_minsal')->name('ws_minsal')->middleware('auth');
            Route::get('diary_lab_report','SuspectCaseController@diary_lab_report')->name('diary_lab_report')->middleware('auth');
            Route::get('diary_by_lab_report','SuspectCaseController@diary_by_lab_report')->name('diary_by_lab_report')->middleware('auth');
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
    Route::prefix('inmuno_tests')->name('inmuno_tests.')->group(function () {
        Route::get('/', 'InmunoTestController@index')->name('index')->middleware('auth');
        Route::get('/create/{search}', 'InmunoTestController@create')->name('create')->middleware('auth');
        Route::get('/{inmunoTest}/edit', 'InmunoTestController@edit')->name('edit')->middleware('auth');
        Route::post('/{store}', 'InmunoTestController@store')->name('store')->middleware('auth');
        Route::put('/update/{inmunoTest}', 'InmunoTestController@update')->name('update')->middleware('auth');
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
    Route::get('/status', 'StatuController@index')->name('statu');
    Route::get('/status/create', 'StatuController@create')->name('statu.create');
    Route::post('/status/store', 'StatuController@store')->name('statu.store');
    Route::get('/status/{statu}/edit', 'StatuController@edit')->name('statu.edit');
    Route::put('/status/update/{statu}', 'StatuController@update')->name('statu.update');

    Route::get('/event_type', 'EventTypeController@index')->name('EventType');
    Route::get('/event_type/create', 'EventTypeController@create')->name('EventType.create');
    Route::post('/event_type/store', 'EventTypeController@store')->name('EventType.store');
    Route::get('/event_type/{eventType}/edit', 'EventTypeController@edit')->name('EventType.edit');
    Route::put('/event_type/update/{eventType}', 'EventTypeController@update')->name('EventType.update');

    Route::get('/request_type', 'RequestTypeController@index')->name('request_type');
    Route::get('/request_type/create', 'RequestTypeController@create')->name('request_type.create');
    Route::post('/request_type/store', 'RequestTypeController@store')->name('request_type.store');
    Route::get('/request_type/{request_type}/edit', 'RequestTypeController@edit')->name('request_type.edit');
    Route::put('/request_type/update/{request_type}', 'RequestTypeController@update')->name('request_type.update');

});

Route::prefix('sanitary_residences')->name('sanitary_residences.')->middleware('auth')->group(function () {
    Route::get('/', 'ResidenceController@home')->name('home');
    Route::get('/users', 'ResidenceController@users')->name('users');
    Route::post('/users', 'ResidenceController@usersStore')->name('users.store');
    //Route::get('/{user}/edit', 'ResidenceController@usersEdit')->name('users.edit');
    Route::delete('/{residenceUser}', 'ResidenceController@usersDestroy')->name('users.destroy');

    Route::prefix('admission')->name('admission.')->group(function () {
    Route::get('/changestatus/{admission}/{status}', 'AdmissionSurveyController@changestatus')->name('changestatus');
    Route::get('/accept/{admission}', 'AdmissionSurveyController@accept')->name('accept');
    Route::get('/inboxaccept', 'AdmissionSurveyController@inboxaccept')->name('inboxaccept');
    Route::get('/rejected/{admission}', 'AdmissionSurveyController@rejected')->name('rejected');
    Route::get('/inboxrejected', 'AdmissionSurveyController@inboxrejected')->name('inboxrejected');
    Route::get('/create/{patient}', 'AdmissionSurveyController@create')->name('create');
    Route::get('/', 'AdmissionSurveyController@index')->name('index');
    Route::get('/inbox', 'AdmissionSurveyController@inbox')->name('inbox');
    Route::post('/', 'AdmissionSurveyController@store')->name('store');
    Route::get('/{admission}/edit', 'AdmissionSurveyController@edit')->name('edit');
    Route::get('/{admission}/seremiadmission', 'AdmissionSurveyController@seremiadmission')->name('seremiadmission');
    Route::put('update/{admission}', 'AdmissionSurveyController@update')->name('update');
    Route::get('/{admission}', 'AdmissionSurveyController@show')->name('show');

    });


    Route::prefix('residences')->name('residences.')->group(function () {
        Route::get('/create', 'ResidenceController@create')->name('create');
        Route::get('/', 'ResidenceController@index')->name('index');
        Route::post('/', 'ResidenceController@store')->name('store');
        Route::get('/{residence}/edit', 'ResidenceController@edit')->name('edit');
        Route::put('update/{residence}', 'ResidenceController@update')->name('update');
        Route::delete('/{residence}', 'ResidenceController@destroy')->name('destroy');
        Route::get('/statusReport', 'ResidenceController@statusReport')->name('statusReport');


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

        Route::get('/bookingByDate','BookingController@bookingByDate')->name('bookingByDate');
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
