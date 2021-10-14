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
Route::get('/secuenciacion', function () {
    return view('secuenciacion');
});
Route::get('/secuenciaciondata', function () {
    return view('secuenciaciondata');
});
Route::get('/adddata', function () {
    return view('adddata');
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
    Route::get('getotheridentification/{other_identification?}','PatientController@getPatientOtherIdentification')->name('getotheridentification')->middleware('auth');
    //Route::get('get/{rut?}','PatientController@getPatient')->name('get')->middleware('auth');
    Route::get('georeferencing','PatientController@georeferencing')->name('georeferencing')->middleware('can:Patient: georeferencing');
    Route::get('/', 'PatientController@index')->name('index')->middleware('can:Patient: list' );
    Route::get('/dialisys/{establishment?}', 'PatientController@index')->name('dialysis.index')->middleware('can:DialysisCenter: user');
    Route::get('positives', 'PatientController@positives')->name('positives')->middleware('can:Patient: list');
    Route::get('/create', 'PatientController@create')->name('create')->middleware('can:Patient: create');
    Route::post('/', 'PatientController@store')->name('store')->middleware('can:Patient: create');
    Route::get('/{patient}/edit', 'PatientController@edit')->name('edit')->middleware('can:Patient: edit');
    Route::put('/{patient}', 'PatientController@update')->name('update')->middleware('can:Patient: edit');
    Route::delete('/{patient}', 'PatientController@destroy')->name('destroy')->middleware('can:Patient: delete');
    Route::get('/export', 'PatientController@export')->name('export');
    Route::get('/exportPositives', 'PatientController@exportPositives')->name('exportPositives');
    Route::get('/in_residence', 'PatientController@inResidence')->name('in_residence');

    Route::get('/{patient}/fhir', 'PatientController@fhir');

    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/create/{search}/{id}', 'ContactPatientController@create')->name('create')->middleware('auth');
        Route::post('/', 'ContactPatientController@store')->name('store')->middleware('auth');
        Route::get('/{contact_patient}/edit', 'ContactPatientController@edit')->name('edit')->middleware('auth');
        Route::put('/{contact_patient}', 'ContactPatientController@update')->name('update')->middleware('auth');
        Route::get('delete/{contact_patient}','ContactPatientController@destroy')->name('destroy')->middleware('auth');;
    });

    Route::prefix('tracings')->name('tracings.')->middleware('auth')->group(function () {
        Route::get('/notifications', 'TracingController@notificationsReport')->name('notifications_report');
        Route::get('/cartoindex', 'TracingController@carToIndex')->name('cartoindex');
        Route::get('/reportbycommune', 'TracingController@reportByCommune')->name('reportbycommune');
        Route::get('/mapbycommunes', 'TracingController@mapByCommune')->name('mapbycommunes');
        Route::get('/mapbyestablishments', 'TracingController@mapByEstablishment')->name('mapbyestablishments');
        Route::get('/communes', 'TracingController@indexByCommune')->name('communes');
        Route::get('/establishments', 'TracingController@indexByEstablishment')->name('establishments');
        Route::get('/completed', 'TracingController@tracingCompleted')->name('completed');
        Route::get('/withouttracing', 'TracingController@withoutTracing')->name('withouttracing');
        Route::get('/withoutevents', 'TracingController@withoutEvents')->name('withoutevents');


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

        Route::prefix('ws')->name('ws.')->group(function (){
//            Route::get('/get_folio_patient/{type_id}/{id}', 'TracingController@getFolioPatientWs')->name('get_folio_patient');
            Route::get('/set_contact_patient/{patient}', 'ContactPatientController@setContactPatientWs')->name('set_contact_patient');
            Route::get('/set_questionnaire_patient/{contact_patient}', 'ContactPatientController@setQuestionnairePatientWs')->name('set_questionnaire_patient');
            Route::get('/set_tracing_bundle/{event}', 'TracingController@setTracingBundleWs')->name('set_tracing_bundle');

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
    Route::post('updateNotification/{suspect_case}','SuspectCaseController@updateNotification')->name('updateNotification');
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
        Route::post('derive','SuspectCaseController@derive')->name('derive')->middleware('auth');
        Route::post('mass_reception','SuspectCaseController@massReception')->name('mass_reception')->middleware('auth');

        Route::post('/search_id','SuspectCaseController@search_id')->name('search_id')->middleware('auth');
        //Route::get('stat', 'SuspectCaseController@stat')->name('stat');

        Route::get('download/{suspect_case}','SuspectCaseController@download')->name('download')->middleware('auth');
        //Route::get('download/{file}','SuspectCaseController@download')->name('download');
        Route::get('file/{suspect_case}','SuspectCaseController@fileDelete')->name('fileDelete')->middleware('auth','can:SuspectCase: file delete');

        Route::get('/index/{laboratory?}','SuspectCaseController@index')->name('index')->middleware('auth','can:SuspectCase: list');

        //DIALISIS
        Route::get('/dialysis/covid/{establishment?}','DialysisPatientController@covid')->name('dialysis.covid')->middleware('auth');
        Route::get('/dialysis/{establishment?}','DialysisPatientController@index')->name('dialysis.index')->middleware('auth');
        Route::post('/dialysis','DialysisPatientController@store')->name('dialysis.store')->middleware('auth');



        Route::get('/ownIndex/{laboratory?}','SuspectCaseController@ownIndex')->name('ownIndex')->middleware('auth','can:SuspectCase: own');
        Route::get('/ownIndexFilter/{laboratory?}','SuspectCaseReportController@ownIndexFilter')->name('ownIndexFilter')->middleware('auth','can:SuspectCase: own');
        Route::get('/notification','SuspectCaseController@notificationInbox')->name('notificationInbox')->middleware('auth','can:Patient: tracing');

        Route::get('/exportSuspectCases/{lab}/{date?}','SuspectCaseController@exportExcel')->name('export')->middleware('auth');
        Route::get('/exportExcelReceptionInbox/{lab}','SuspectCaseController@exportExcelReceptionInbox')->name('exportExcelReceptionInbox')->middleware('auth');


        //Route::get('/create','SuspectCaseController@create')->name('create')->middleware('auth','can:SuspectCase: create');
        //Route::post('/','SuspectCaseController@store')->name('store')->middleware('auth','can:SuspectCase: create');
        Route::get('/admission','SuspectCaseController@admission')->name('admission')->middleware('auth','can:SuspectCase: admission');
        Route::get('/search','SuspectCaseController@search')->name('search')->middleware('auth','can:SuspectCase: admission');
        Route::post('/admission','SuspectCaseController@storeAdmission')->name('store_admission')->middleware('auth','can:SuspectCase: admission');
        Route::get('/{suspect_case}/edit','SuspectCaseController@edit')->name('edit')->middleware('auth','can:SuspectCase: edit');
        Route::put('/{suspect_case}','SuspectCaseController@update')->name('update')->middleware('auth','can:SuspectCase: edit');
        Route::patch('/{suspect_case}','SuspectCaseController@positiveCondition')->name('positiveCondition')->middleware('auth','can:SuspectCase: edit');
        Route::delete('/{suspect_case}','SuspectCaseController@destroy')->name('destroy')->middleware('auth','can:SuspectCase: delete');
        Route::get('/{suspect_case}/notificationForm','SuspectCaseController@notificationForm')->name('notificationForm')->middleware('auth','can:SuspectCase: admission');
        Route::get('/{suspect_case}/notificationFormSmall','SuspectCaseController@notificationFormSmall')->name('notificationFormSmall')->middleware('auth','can:SuspectCase: admission');
        Route::post('/notificationFormSmallBulk','SuspectCaseController@notificationFormSmallBulk')->name('notificationFormSmallBulk')->middleware('auth','can:SuspectCase: admission');

        Route::get('/index_import_results','SuspectCaseController@index_import_results')->name('index_import_results')->middleware('auth');
        Route::post('/results_import', 'SuspectCaseController@results_import')->name('results_import');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/tracingbycommunes','SuspectCaseReportController@tracingByCommunes')->name('tracingbycommunes');
            Route::get('/positives','SuspectCaseReportController@positives')->name('positives')->middleware('auth','can:Report: positives');
            Route::get('case_tracing','SuspectCaseReportController@case_tracing')->name('case_tracing')->middleware('auth','can:Patient: tracing');
            Route::get('tracing_minsal','SuspectCaseReportController@tracing_minsal')->name('tracing_minsal')->middleware('auth','can:Patient: tracing');
            Route::get('tracing_minsal_by_patient','SuspectCaseReportController@tracing_minsal_by_patient')->name('tracing_minsal_by_patient')->middleware('auth','can:Patient: tracing');
            Route::get('case_tracing/export', 'SuspectCaseReportController@case_tracing_export')->name('case_tracing.export');
            Route::get('/gestants','SuspectCaseReportController@gestants')->name('gestants')->middleware('auth','can:Report: gestants');
            // Route::get('case_chart','SuspectCaseController@case_chart')->name('case_chart')->middleware('auth');
            Route::match(['get','post'],'case_chart','SuspectCaseReportController@case_chart')->middleware('auth')->name('case_chart');
            Route::match(['get','post'],'exams_with_result','SuspectCaseReportController@exams_with_result')->middleware('auth','can:Report: exams with result')->name('exams_with_result');
            Route::get('/minsal/{laboratory}','SuspectCaseReportController@report_minsal')->name('minsal')->middleware('auth');
            Route::get('/reception_report/{laboratory}','SuspectCaseReportController@reception_report')->name('reception_report')->middleware('auth');
            // Route::get('/minsal_ws','SuspectCaseReportController@report_minsal_ws')->name('minsal_ws')->middleware('auth');
            Route::match(['get','post'],'/minsal_ws','SuspectCaseReportController@report_minsal_ws')->name('minsal_ws')->middleware('auth');
            Route::get('/seremi/{laboratory}','SuspectCaseReportController@report_seremi')->name('seremi')->middleware('auth');
            Route::get('/positivesByDateRange','SuspectCaseReportController@positivesByDateRange')->name('positivesByDateRange')->middleware('auth');
            Route::get('/positives_own','SuspectCaseReportController@positivesOwn')->name('positives_own')->middleware('auth');
            Route::get('hospitalized','SuspectCaseReportController@hospitalized')->name('hospitalized')->middleware('auth','can:Report: hospitalized');
            Route::get('hospitalizedByUserCommunes','SuspectCaseReportController@hospitalizedByUserCommunes')->name('hospitalizedByUserCommunes')->middleware('auth','can:Report: hospitalized commune');
            Route::get('deceased','SuspectCaseReportController@deceased')->name('deceased')->middleware('auth','can:Report: deceased');
            Route::get('requires_licence','SuspectCaseReportController@requires_licence')->name('requires_licence')->middleware('auth', 'can:Report: requires licence');
            Route::get('user_performance','SuspectCaseReportController@user_performance')->name('user_performance')->middleware('auth', 'can:Report: user performance');
            Route::get('without_reception','SuspectCaseReportController@withoutReception')->name('without_reception')->middleware('auth');
            Route::get('pending_more_than_two_days','SuspectCaseReportController@pendingMoreThanTwoDays')->name('pending_more_than_two_days')->middleware('auth', 'can:Report: more than two days');
            Route::get('suspect_case_by_commune','SuspectCaseReportController@suspectCaseByCommune')->name('suspect_case_by_commune')->middleware('auth', 'can:Report: suspect cases by commune');
            Route::get('/cases_without_results','SuspectCaseReportController@casesWithoutResults')->name('cases_without_results')->middleware('auth');
            Route::get('/cases_with_barcodes','SuspectCaseReportController@casesWithBarcodes')->name('cases_with_barcodes')->middleware('auth');
            Route::get('/cases_by_ids_index','SuspectCaseReportController@casesByIdsIndex')->name('cases_by_ids_index')->middleware('auth');
            Route::post('/export_excel_by_cases_ids','SuspectCaseReportController@exportExcelByCasesIds')->name('export_excel_by_cases_ids')->middleware('auth');
            Route::get('/allrapidtests','SuspectCaseReportController@allRapidTests')->name('all_rapid_tests')->middleware('auth');

        });
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/','SuspectCaseReportController@positives')->name('index')->middleware('auth','can:Report: other');
            Route::get('historical_report','SuspectCaseController@historical_report')->name('historical_report')->middleware('auth','can:Report: historical');
            Route::get('/minsal-export/{laboratory}','SuspectCaseController@exportMinsalExcel')->name('exportMinsal')->middleware('auth');
            Route::get('/seremi-export/{laboratory}','SuspectCaseController@exportSeremiExcel')->name('exportSeremi')->middleware('auth');
            Route::get('/ws_minsal','SuspectCaseReportController@ws_minsal')->name('ws_minsal')->middleware('auth');
            Route::get('/ws_minsal_pendings_creation','SuspectCaseReportController@ws_minsal_pendings_creation')->name('ws_minsal_pendings_creation')->middleware('auth');
            Route::get('/ws_minsal_pendings_reception','SuspectCaseReportController@ws_minsal_pendings_reception')->name('ws_minsal_pendings_reception')->middleware('auth');
            Route::get('/ws_minsal_pendings_result','SuspectCaseReportController@ws_minsal_pendings_result')->name('ws_minsal_pendings_result')->middleware('auth');
            Route::get('diary_lab_report','SuspectCaseController@diary_lab_report')->name('diary_lab_report')->middleware('auth');
            Route::get('positive_average_by_commune','SuspectCaseController@positive_average_by_commune')->name('positive_average_by_commune')->middleware('auth');
            Route::get('diary_by_lab_report','SuspectCaseController@diary_by_lab_report')->name('diary_by_lab_report')->middleware('auth');
            Route::get('estadistico_diario_covid19','SuspectCaseController@estadistico_diario_covid19')->name('estadistico_diario_covid19')->middleware('auth');
        });

        Route::prefix('barcode_reception')->name('barcode_reception.')->group(function(){
            Route::get('/','SuspectCaseController@barcodeReceptionIndex')->name('index')->middleware('auth','can:SuspectCase: reception');
            Route::get('/reception','SuspectCaseController@barcodeReception')->name('reception')->middleware('auth','can:SuspectCase: reception');
            Route::get('/forget_cases_received','SuspectCaseController@barcodeReceptionForgetCasesReceived')->name('forget_cases_received')->middleware('auth','can:SuspectCase: reception');
        });

        Route::get('ws_test', 'SuspectCaseController@ws_test')->name('ws_test');
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

    Route::prefix('rapid_tests')->name('rapid_tests.')->group(function () {
        //Route::get('/', 'InmunoTestController@index')->name('index')->middleware('auth');
        //Route::get('/create/{search}', 'InmunoTestController@create')->name('create')->middleware('auth');
        Route::get('/{inmunoTest}/edit', 'InmunoTestController@edit')->name('edit')->middleware('auth');
        Route::post('/{store}', 'RapidTestController@store')->name('store')->middleware('auth');
        //Route::put('/update/{inmunoTest}', 'InmunoTestController@update')->name('update')->middleware('auth');
    });

    Route::prefix('bulk_load')->name('bulk_load.')->group(function () {
        Route::get('/','SuspectCaseController@index_bulk_load')->name('index')->middleware('auth');
        Route::post('/import', 'SuspectCaseController@bulk_load_import')->name('import.excel');
    });

    Route::prefix('bulk_load_from_pntm')->name('bulk_load_from_pntm.')->group(function () {
        Route::get('/','SuspectCaseController@index_bulk_load_from_pntm')->name('index')->middleware('auth');
        Route::post('/import','SuspectCaseController@bulk_load_import_from_pntm')->name('import.excel')->middleware('auth');
        Route::post('/importpassport','SuspectCaseController@bulk_load_import_from_pntm_passport')->name('import.excel.passport')->middleware('auth');
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


    // Route::get('/dialysis_center', 'DialysisCenterController@index')->name('dialysis_center');
    // Route::get('/dialysis_center/create', 'DialysisCenterController@create')->name('dialysis_center.create');
    // Route::post('/dialysis_center/store', 'DialysisCenterController@store')->name('dialysis_center.store');
    // Route::get('/dialysis_center/{dialysis_center}/edit', 'DialysisCenterController@edit')->name('dialysis_center.edit');
    // Route::put('/dialysis_center/update/{dialysis_center}', 'DialysisCenterController@update')->name('dialysis_center.update');

    Route::get('/request_type', 'RequestTypeController@index')->name('request_type');
    Route::get('/request_type/create', 'RequestTypeController@create')->name('request_type.create');
    Route::post('/request_type/store', 'RequestTypeController@store')->name('request_type.store');
    Route::get('/request_type/{request_type}/edit', 'RequestTypeController@edit')->name('request_type.edit');
    Route::put('/request_type/update/{request_type}', 'RequestTypeController@update')->name('request_type.update');


    Route::get('/establishment', 'EstablishmentController@index')->name('establishment');
    Route::get('/establishment/create', 'EstablishmentController@create')->name('establishment.create');
    Route::post('/establishment/store', 'EstablishmentController@store')->name('establishment.store');
    Route::get('/establishment/{establishment}/edit', 'EstablishmentController@edit')->name('establishment.edit');
    Route::put('/establishment/update/{establishment}', 'EstablishmentController@update')->name('establishment.update');

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
    Route::delete('/{admission}', 'AdmissionSurveyController@destroy')->name('destroy');

    });


    Route::prefix('residences')->name('residences.')->group(function () {
        Route::get('/create', 'ResidenceController@create')->name('create');
        Route::get('/', 'ResidenceController@index')->name('index');
        Route::post('/', 'ResidenceController@store')->name('store');
        Route::get('/{residence}/edit', 'ResidenceController@edit')->name('edit');
        Route::put('update/{residence}', 'ResidenceController@update')->name('update');
        Route::delete('/{residence}', 'ResidenceController@destroy')->name('destroy');
        Route::get('/statusReport', 'ResidenceController@statusReport')->name('statusReport');
        Route::get('/map', 'ResidenceController@map')->name('map');


    });

    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', 'RoomController@index')->name('index');
        Route::get('/create', 'RoomController@create')->name('create');
        Route::post('/', 'RoomController@store')->name('store');
        Route::get('/{room}/edit', 'RoomController@edit')->name('edit');
        Route::put('/{room}', 'RoomController@update')->name('update');
        Route::delete('/{room}', 'RoomController@destroy')->name('destroy');
    });

    Route::prefix('bookings')->name('bookings.')->group(function () {

        Route::get('/bookingByDate','BookingController@bookingByDate')->name('bookingByDate');
        Route::get('/excelall','BookingController@excelall')->name('excelall');
        Route::get('/excelvitalsign','BookingController@excelvitalsign')->name('excelvitalsign');
        Route::get('/excel/{booking}','BookingController@excel')->name('excel');
        Route::delete('/{booking}', 'BookingController@destroy')->name('destroy');
        Route::get('/residence/{residence}', 'BookingController@index')->name('index');
        Route::get('/createfrompatient/{patient}', 'BookingController@createfrompatient')->name('createfrompatient');
        Route::get('/create', 'BookingController@create')->name('create');
        Route::get('/{booking}', 'BookingController@show')->name('show');
        Route::post('/', 'BookingController@store')->name('store');
        Route::get('/{booking}', 'BookingController@show')->name('show');
        Route::get('/{booking}/release', 'BookingController@showRelease')->name('showrelease');
        // Route::get('/{booking}/edit', 'BookingController@edit')->name('edit');
        Route::put('/{booking}', 'BookingController@update')->name('update');
        Route::put('/{booking}/returnbooking', 'BookingController@returnbooking')->name('returnbooking');
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

Route::prefix('sequencing')->name('sequencing.')->middleware('auth')->group(function () {
    Route::get('/', 'SequencingCriteriaController@index')->name('index');
    Route::get('/indexsend', 'SequencingCriteriaController@indexsend')->name('indexsend');
    Route::get('/create/{suspect_case}','SequencingCriteriaController@create')->name('create');
    Route::get('/{sequencingCriteria}/edit','SequencingCriteriaController@edit')->name('edit');
    Route::put('/{sequencingCriteria}', 'SequencingCriteriaController@update')->name('update');
    Route::put('/{sequencingCriteria}/send', 'SequencingCriteriaController@send')->name('send');
    Route::delete('/{sequencingCriteria}', 'SequencingCriteriaController@destroy')->name('destroy');


});


Route::prefix('pending_patient')->name('pending_patient.')->middleware('auth')->group(function () {
   Route::get('/create', 'PendingPatientController@create')->name('create');
   Route::post('/store', 'PendingPatientController@store')->name('store');
   Route::get('/{pending_patient}/edit', 'PendingPatientController@edit')->name('edit');
   Route::get('/', 'PendingPatientController@index')->name('index');
   Route::put('/{pending_patient}', 'PendingPatientController@update')->name('update');
   Route::get('/{pending_patient}', 'PendingPatientController@destroy')->name('destroy');
   Route::get('/export_excel_by_status/{selectedStatus}','PendingPatientController@exportExcelByStatus')->name('export_excel_by_status')->middleware('auth');

});
