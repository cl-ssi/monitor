<?php

namespace App\Http\Controllers;
use App\Log;
use App\Rules\UniqueSampleDateByPatient;
use GuzzleHttp\Client;
use App\SuspectCase;
use App\Patient;
use App\Demographic;
use App\RapidTest;
use App\File;
use App\User;
use App\EstablishmentUser;
use App\Region;
use App\Commune;
use App\Laboratory;
use App\Establishment;
use App\ReportBackup;
use App\SampleOrigin;
use App\Country;
use App\Tracing\Tracing;
use App\BulkLoadRecord;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Mail\NewPositive;
use App\Mail\NewNegative;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuspectCasesExport;
use App\Exports\HetgSuspectCasesExport;
use App\Exports\UnapSuspectCasesExport;
use App\Exports\MinsalSuspectCasesExport;
use App\Exports\SeremiSuspectCasesExport;
use App\Imports\PatientImport;
use App\Imports\DemographicImport;
use App\Imports\SuspectCaseImport;
use App\SequencingCriteria;

use App\WSMinsal;
use MongoDB\Driver\Session;
use PDO;
use Redirect;
use Throwable;

class SuspectCaseController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(request $request, Laboratory $laboratory){
      $collection = collect(['positivos', 'negativos', 'pendientes', 'rechazados', 'indeterminados']);
      $filtro = collect([]);
      $collection->each(function ($item, $key) use ($request, $filtro){
                  switch ($item) {
              case "positivos":
                  $request->get('positivos')=="on"?$filtro->push('positive'):true;
                  break;
              case "negativos":
                  $request->get('negativos')=="on"?$filtro->push('negative'):true;
                  break;
              case "pendientes":
                  $request->get('pendientes')=="on"?$filtro->push('pending'):true;
                  break;
              case "rechazados":
                  $request->get('rechazados')=="on"?$filtro->push('rejected'):true;
                  break;
              case "indeterminados":
                  $request->get('indeterminados')=="on"?$filtro->push('undetermined'):true;
                  break;
          }
      });

      $patients = Patient::getPatientsBySearch($request->get('text'));
      if(!empty($laboratory->id)){

//          $cases['total'] = SuspectCase::where('laboratory_id',$laboratory->id)->whereNotNull('reception_at')->count();
//          $cases['positivos']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','positive')->count();
//          $cases['negativos']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','negative')->count();
//          $cases['pendientes']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','pending')->count();
//          $cases['rechazados']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','rejected')->count();
//          $cases['indeterminados']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','undetermined')->count();

          $cases['total'] = SuspectCase::where('laboratory_id',$laboratory->id)->whereNotNull('reception_at')->count();
          $cases['positivos']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','positive')->whereNotNull('reception_at')->count();
          $cases['negativos']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','negative')->whereNotNull('reception_at')->count();
          $cases['pendientes']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','pending')->whereNotNull('reception_at')->count();
          $cases['rechazados']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','rejected')->whereNotNull('reception_at')->count();
          $cases['indeterminados']=SuspectCase::where('laboratory_id',$laboratory->id)->where('pcr_sars_cov_2','undetermined')->whereNotNull('reception_at')->count();

          DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
          $suspectCases = SuspectCase::getCaseByPatientLaboratory($patients, $laboratory->id)
                               ->latest('id')
                               ->whereIn('pcr_sars_cov_2',$filtro)
                               ->whereNotNull('reception_at')
                               ->paginate(200);
          DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
     }
     else{
          $laboratory = null;
//          $cases['total'] = SuspectCase::whereNotNull('laboratory_id')->count();
//          $cases['positivos']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','positive')->count();
//          $cases['negativos']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','negative')->count();
//          $cases['pendientes']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','pending')->count();
//          $cases['rechazados']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','rejected')->count();
//          $cases['indeterminados']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','undetermined')->count();

         $cases['total'] = SuspectCase::whereNotNull('laboratory_id')->count();
         $cases['positivos']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','positive')->whereNotNull('reception_at')->count();
         $cases['negativos']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','negative')->whereNotNull('reception_at')->count();
         $cases['pendientes']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','pending')->whereNotNull('reception_at')->count();
         $cases['rechazados']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','rejected')->whereNotNull('reception_at')->count();
         $cases['indeterminados']=SuspectCase::whereNotNull('laboratory_id')->where('pcr_sars_cov_2','undetermined')->whereNotNull('reception_at')->count();

          DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
          $suspectCases = SuspectCase::getCaseByPatient($patients)
                              ->latest('id')
                              ->whereNotNull('laboratory_id')
                              ->whereIn('pcr_sars_cov_2',$filtro)
                              ->whereNotNull('reception_at')
                              ->paginate(200);
          DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      }
      return view('lab.suspect_cases.index', compact('suspectCases','request','laboratory','cases'));
  }

    /**
    * Muestra exámenes asociados al establishment de usuario actual.
    * @param Request $request
    * @param Laboratory $laboratory
    * @return Application|Factory|View
    */
    public function ownIndex(request $request, Laboratory $laboratory)
    {
      $searchText = $request->get('text');
      $arrayFilter = (empty($request->filter)) ? array() : $request->filter;

//      $suspectCasesTotal = SuspectCase::where(function($q){
//          $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
//              ->orWhere('user_id', Auth::user()->id);
//      })->get();

        $suspectCasesTotal = SuspectCase::whereNotNull('reception_at')
            ->where(function($q){
                $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
                    ->orWhere('user_id', Auth::user()->id);
            })->get();

//      $suspectCases = SuspectCase::where(function($q){
//          $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
//              ->orWhere('user_id', Auth::user()->id);
//      })
//          ->patientTextFilter($searchText)
//          ->whereIn('pcr_sars_cov_2', $arrayFilter)
//          ->paginate(200);

        $suspectCases = SuspectCase::whereNotNull('reception_at')
            ->where(function($q){
                $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
                    ->orWhere('user_id', Auth::user()->id);
            })
            ->patientTextFilter($searchText)
            ->whereIn('pcr_sars_cov_2', $arrayFilter)
            ->paginate(200);

      return view('lab.suspect_cases.ownIndex', compact('suspectCases', 'arrayFilter', 'searchText', 'laboratory', 'suspectCasesTotal'));
    }

    /**
     * Muestra exámenes asociados a la comunas del usuario.
     * @return Application|Factory|View
     */
    public function notificationInbox()
    {
        $from = Carbon::now()->subDays(3);
        $to = Carbon::now();

            /*
            where(function($q){
                                $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'));
                            })
                            ->
                */
        $suspectCases = SuspectCase::whereHas('patient', function($q){
                                $q->whereHas('demographic', function($q){
                                        $q->whereIn('commune_id',auth()->user()->communes());
                                });
                        })
                        ->whereNotIn('pcr_sars_cov_2', ['pending','positive','undetermined'])
                        ->whereNull('notification_at')
                        ->whereBetween('created_at', [$from, $to])
                        ->get();

        // dd($suspectCases);

        return view('lab.suspect_cases.notification_inbox', compact('suspectCases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $external_labs = Laboratory::where('external',1)->orderBy('name')->get();
        $establishments = Establishment::orderBy('name','ASC')->get();

        /* FIX codigo duro */
        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();

        $sampleOrigins = SampleOrigin::orderBy('alias')->get();
        return view('lab.suspect_cases.create',compact('sampleOrigins','establishments','external_labs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function search()
    {
        return view('lab.suspect_cases.search');

    }



    public function admission()
    {
        if(!Auth::user()->laboratory_id){
            session()->flash('warning', 'Ud. debe tener asignado un laboratorio para agregar un nuevo caso.');
            return redirect()->back()->withInput();
        }

        $regions = Region::orderBy('id','ASC')->get();
        $communes = Commune::orderBy('id','ASC')->get();
        $countries = Country::select('name')->orderBy('id', 'ASC')->get();

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        //$establishments = Establishment::whereIn('commune_id',$env_communes)->where('name','<>','Otros')->orderBy('name','ASC')->get();

        $establishmentsusers = EstablishmentUser::where('user_id',Auth::id())->get();

        //dd($establishmentsusers);

        $sampleOrigins = SampleOrigin::orderBy('alias')->get();
        return view('lab.suspect_cases.admission',compact('sampleOrigins','regions', 'communes','establishmentsusers', 'countries'));
    }


    public function reception(Request $request, SuspectCase $suspectCase, $barcodeReception = false)
    {
        //Obtiene datos de recepcion actuales
        $receptor_id_old = $suspectCase->receptor_id;
        $reception_at_old = $suspectCase->reception_at;

        /* Recepciona en sistema */
        $suspectCase->receptor_id = Auth::id();
        $suspectCase->reception_at = date('Y-m-d H:i:s');
        if (!$suspectCase->minsal_ws_id) $suspectCase->laboratory_id = Auth::user()->laboratory->id;
        $suspectCase->save();

        /* Webservice minsal */
        //####### recepciona en webservice ########
        if (env('ACTIVA_WS', false) == true) {
            if ($suspectCase->laboratory_id != null) {
                if ($suspectCase->laboratory->minsal_ws == true) {
                    if ($suspectCase->minsal_ws_id) {
                        // recepciona en minsal
                        $response = WSMinsal::recepciona_muestra($suspectCase);
                        if ($response['status'] == 0) {
                            // Si en pntm esta recepcionado y en monitor no
                            $responseSampleStatus = WSMinsal::obtiene_estado_muestra($suspectCase);
                            if ($responseSampleStatus['status'] == 1 && $responseSampleStatus['sample_status'] == 3 && $suspectCase->reception_at != NULL) {
                                session()->flash('success', 'Se ha recepcionada la muestra: '
                                    . $suspectCase->id . ' en laboratorio: '
                                    . Auth::user()->laboratory->name);

                                if ($barcodeReception) return true;
                                return redirect()->back();
                            }else{
                                session()->flash('warning', 'Error al recepcionar muestra ' . $suspectCase->id . ' en MINSAL. ' . $response['msg'] . ".");
                                $suspectCase->receptor_id = $receptor_id_old;
                                $suspectCase->reception_at = $reception_at_old;
                                $suspectCase->save();
                                if ($barcodeReception) return false;
                                return redirect()->back()->withInput();
                            }
                        }
                    }
                }
            }
        }

        session()->flash('success', 'Se ha recepcionada la muestra: '
            . $suspectCase->id . ' en laboratorio: '
            . Auth::user()->laboratory->name);

        if ($barcodeReception) return true;
        return redirect()->back();
    }

//    public function reception(Request $request, SuspectCase $suspectCase) //recepcion antes en pntm y luego monitor
//    {
//        /* Webservice minsal */
//        //####### recepciona en webservice ########
//        if (env('ACTIVA_WS', false) == true) {
//            if ($suspectCase->minsal_ws_id) {
//                if ($suspectCase->laboratory_id != null) {
//                    if ($suspectCase->laboratory->minsal_ws == true) {
//                        // recepciona en minsal
//                        $response = WSMinsal::recepciona_muestra($suspectCase);
//                        if ($response['status'] == 1) {
//                            $suspectCase->receptor_id = Auth::id();
//                            $suspectCase->reception_at = date('Y-m-d H:i:s');
//                            $suspectCase->save();
//                        } else {
//                            session()->flash('info', 'Error al recepcionar muestra ' . $suspectCase->id . ' en MINSAL. ' . $response['msg'] . ".");
////                            $suspectCase->receptor_id = NULL;
////                            $suspectCase->reception_at = NULL;
////                            $suspectCase->save();
//                            return redirect()->back()->withInput();
//                        }
//                    }else{
//                        $suspectCase->receptor_id = Auth::id();
//                        $suspectCase->reception_at = date('Y-m-d H:i:s');
//                        if (!$suspectCase->minsal_ws_id) $suspectCase->laboratory_id = Auth::user()->laboratory->id;
//                    }
//                } else {
//                    session()->flash('info', 'Error al recepcionar muestra ' . $suspectCase->id . ' en MINSAL. ' . 'No existe laboratory_id' . ".");
//                    return redirect()->back()->withInput();
//                }
//            } else {
//                $suspectCase->receptor_id = Auth::id();
//                $suspectCase->reception_at = date('Y-m-d H:i:s');
//                $suspectCase->laboratory_id = Auth::user()->laboratory->id;
//            }
//        } else {
//            $suspectCase->receptor_id = Auth::id();
//            $suspectCase->reception_at = date('Y-m-d H:i:s');
//            if (!$suspectCase->minsal_ws_id) $suspectCase->laboratory_id = Auth::user()->laboratory->id;
//        }
//
//        session()->flash('info', 'Se ha recepcionada la muestra: '
//            . $suspectCase->id . ' en laboratorio: '
//            . Auth::user()->laboratory->name);
//
//        return redirect()->back();
//    }

    /**
     * Recepciona masivamente muestras
     * @param Request $request
     */
    public function massReception(Request $request)
    {
        //VALIDA QUE SE HAYA SELECCIONADO UN EXAMEN
        $request->validate([
            'casos_seleccionados' => ['required']
        ],
            [
                'required' => 'Debe seleccionar al menos un exámen.'
            ]);

        $idsCasesReceptionArray = $request->get('casos_seleccionados');
        $errorMsg = '';
//        $oldCases = SuspectCase::find($request->get('casos_seleccionados'));

        if (env('ACTIVA_WS', false) == true) {
            $cases = SuspectCase::whereIn('id', $idsCasesReceptionArray)->get();
            foreach ($cases as $case) {
                if ($case->minsal_ws_id) {
                    if ($case->laboratory_id != null) {
                        if ($case->laboratory->minsal_ws == true) {
                            // recepciona en minsal
                            $response = WSMinsal::recepciona_muestra($case);
                            if ($response['status'] == 1) {
                                $case->receptor_id = Auth::id();
                                $case->reception_at = date('Y-m-d H:i:s');
                                $case->save();
                            } else {
                                $responseSampleStatus = WSMinsal::obtiene_estado_muestra($case);
                                if ($responseSampleStatus['status'] == 1 && $responseSampleStatus['sample_status'] == 3) {
                                    $case->receptor_id = Auth::id();
                                    $case->reception_at = date('Y-m-d H:i:s');
                                    $case->save();
                                }else{
                                    $errorMsg = $errorMsg . 'Error al recepcionar muestra ' . $case->id . ' en MINSAL. ' . $response['msg'] . "<br>";
                                }
                            }
                        }else{
                            $case->receptor_id = Auth::id();
                            $case->reception_at = date('Y-m-d H:i:s');
                            $case->save();
                        }
                    }else{
                        $errorMsg = $errorMsg . 'No es posible modificar laboratorio en PNTM para caso ' . $case->id . ' No existe laboratory_id.' . '<br>';
                    }
                }else{
                    $case->receptor_id = Auth::id();
                    $case->reception_at = date('Y-m-d H:i:s');
                    $case->laboratory_id = Auth::user()->laboratory->id;
                    $case->save();
                }
            }
        }else{
            /* Recepciona en sistema sin pasar por ws */
            //todo quitar update de laboratory_id cuando todos los casos tengan laboratory_id?
            DB::table('suspect_cases')
                ->whereIn('id', $idsCasesReceptionArray)
                ->update(['receptor_id' => Auth::id(),
                    'reception_at' => date('Y-m-d H:i:s'),
                    'laboratory_id' => Auth::user()->laboratory->id]);
        }

        if($errorMsg == ''){
            session()->flash('success', 'Se recepcionaron muestras correctamente.');
        }else{
            session()->flash('warning', $errorMsg);
        }
        return redirect()->back();
    }

    /**
     * Derivar casos a otros labs
     * @param Request $request
     */
    public function derive(Request $request)
    {
        //VALIDA QUE SE HAYA SELECCIONADO UN EXAMEN
        $request->validate([
            'casos_seleccionados' => ['required']
        ],
        [
            'required' => 'Debe seleccionar al menos un exámen.'
        ]);

        $idsCasosDerivarArray = $request->get('casos_seleccionados');
        $laboratory = Laboratory::find($request->get('laboratory_id_derive'));
        $errorMsg = '';

        //SE ENVIA A WS
        if (env('ACTIVA_WS', false) == true) {
            $cases = SuspectCase::whereIn('id', $idsCasosDerivarArray)->get();
            foreach ($cases as $case) {
                if ($case->minsal_ws_id) {
                    if ($case->laboratory_id != null) {
                        if ($case->laboratory->minsal_ws == true) {
                            if ($laboratory->id_openagora != null) {
                                $response = WSMinsal::cambia_laboratorio($case, $laboratory->id_openagora);

                                if ($response['status'] == 1) {
                                    $this->saveDerivation($laboratory, $case);
                                } else {
                                    $errorMsg = $errorMsg . 'Error al intentar cambiar laboratorio de la muestra ' . $case->id . ' en MINSAL. ' . $response['msg'] . '<br>';
                                }
                            } else {
                                $errorMsg = $errorMsg . 'No es posible modificar laboratorio en PNTM para caso ' . $case->id . '. No existe *id PNTM* del laboratorio.' . '<br>';
                            }
                        }else{
                            $this->saveDerivation($laboratory, $case);
                        }
                    }else{
                        $errorMsg = $errorMsg . 'No es posible modificar laboratorio en PNTM para caso '. $case->id  . '. No existe laboratory_id.' . '<br>';
                    }
                }else{
                    $this->saveDerivation($laboratory, $case);
                }
            }
        }else{
            //SE GUARDA EN BD ESMERALDA SIN PASAR POR WS
            if($laboratory->external){
                $derivedCasesCant = DB::table('suspect_cases')
                    ->whereIn('id', $idsCasosDerivarArray)
                    ->update(['external_laboratory' => $laboratory->name,
                        'sent_external_lab_at' => date("Y-m-d H:i:s")]);

                DB::table('suspect_cases')
                    ->whereIn('id', $idsCasosDerivarArray)
                    ->update(['reception_at' => date('Y-m-d H:i:s'),
                        'receptor_id' => Auth::id()]);
            }else{
                $derivedCasesCant = DB::table('suspect_cases')
                    ->whereIn('id', $idsCasosDerivarArray)
                    ->update(['laboratory_id' => $laboratory->id,
                        'derivation_internal_lab_at' => date("Y-m-d H:i:s")]);

                DB::table('suspect_cases')
                    ->whereIn('id', $idsCasosDerivarArray)
                    ->update(['reception_at' => NULL,
                        'receptor_id' => NULL]);
            }
        }

        if($errorMsg == ''){
            session()->flash('success', "Se derivaron los casos a laboratorio $laboratory->alias ");
        }else{
            session()->flash('warning', $errorMsg);
        }

        return redirect()->back();

    }

    /**
     * Guarda derivación segun sea lab externo o no.
     * @param Laboratory $laboratory
     * @param SuspectCase $case
     */
    private function saveDerivation(Laboratory $laboratory, SuspectCase $case): void
    {
        if ($laboratory->external) {
            $case->external_laboratory = $laboratory->name;
            $case->sent_external_lab_at = date("Y-m-d H:i:s");
            $case->reception_at = date('Y-m-d H:i:s');
            $case->receptor_id = Auth::id();
            $case->save();
        } else {
            $case->laboratory_id = $laboratory->id;
            $case->derivation_internal_lab_at = date("Y-m-d H:i:s");
            $case->reception_at = NULL;
            $case->receptor_id = NULL;
            $case->save();
        }
    }

    /**
     * NO UTILIZAR
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        if ($request->id == null) {
            $patient = new Patient($request->All());
        } else {
            $patient = Patient::find($request->id);
            $patient->fill($request->all());
        }
        $patient->save();

        $suspectCase = new SuspectCase($request->All());
        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))->add(1, 'days')->weekOfYear;

        /* Recepcionar si soy del laboratorio */
        $suspectCase->laboratory_id = Auth::user()->laboratory_id;
        $suspectCase->receptor_id = Auth::id();
        $suspectCase->user_id = Auth::id();
        $suspectCase->run_medic = $request->run_medic_s_dv . "-" . $request->run_medic_dv;

        $suspectCase->reception_at = date('Y-m-d H:i:s');

        if(!$request->input('pcr_sars_cov_2')) {
            $suspectCase->pcr_sars_cov_2 = 'pending';
        }

        if($request->input('pcr_sars_cov_2_at')){
            $suspectCase->pcr_sars_cov_2_at = $request->input('pcr_sars_cov_2_at').' '.date('H:i:s');
        }

        $suspectCase->sample_at = $request->input('sample_at').' '.date('H:i:s');

        $patient->suspectCases()->save($suspectCase);

        //guarda archivos
        if ($request->hasFile('forfile')) {
            foreach ($request->file('forfile') as $file) {
                $filename = $file->getClientOriginalName();
                $fileModel = new File;
                $fileModel->file = $file->store('files');
                $fileModel->name = $filename;
                $fileModel->suspect_case_id = $suspectCase->id;
                $fileModel->save();
            }
        }

        if (env('APP_ENV') == 'production') {
            if ($suspectCase->pcr_sars_cov_2 == 'positive') {
                $emails  = explode(',', env('EMAILS_ALERT'));
                $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
                Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($suspectCase));
            }
        }

        //$log = new Log();
        //$log->old = $suspectCase;
        //$log->new = $suspectCase;
        //$log->save();

        session()->flash('success', 'Se ha creado el caso número: <h3>' . $suspectCase->id . '</h3>');
        return redirect()->route('lab.suspect_cases.index',$suspectCase->laboratory_id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmission(Request $request)
    {
        $request->validate([
           'id' => new UniqueSampleDateByPatient($request->sample_at)
        ]);

        /* Si existe el paciente lo actualiza, si no, crea uno nuevo */
        if ($request->id == null) {
            $patient = new Patient($request->All());
        } else {
            $patient = Patient::find($request->id);
            $patient->fill($request->all());
        }
        $patient->save();

        $suspectCase = new SuspectCase($request->All());
        $suspectCase->user_id = Auth::id();

        if(trim($request->run_medic_s_dv) == ''){
            $suspectCase->run_medic = NULL;
        }else{
            $suspectCase->run_medic = $request->run_medic_s_dv . "-" . $request->run_medic_dv;
        }


        /* Calcula la semana epidemiológica */
        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))
                                                    ->add(1, 'days')->weekOfYear;

        /* Marca como pendiente el resultado, no viene en el form */
        $suspectCase->pcr_sars_cov_2 = 'pending';

        /* Si viene la fecha de nacimiento entonces calcula la edad y la almaceno en suspectCase */
        if($request->input('birthday')) {
            $suspectCase->age = $patient->age;
        }

        /* Si se crea el caso por alguien con laboratorio asignado */
        /* La muestra se recepciona inmediatamente */
        if(Auth::user()->laboratory_id) {
            $suspectCase->laboratory_id = Auth::user()->laboratory_id;
        }



        // ws minsal: previo a guardar, se verifica que la información sea correcta.
        //  if (env('ACTIVA_WS', false) == true) {
        //      if ($suspectCase->laboratory->minsal_ws == true) {
        //          $response = WSMinsal::valida_crea_muestra($request);
        //          $ws_minsal_id = $response['msg'];
        //          if ($response['status'] == 0) {
        //              session()->flash('warning', 'Error al validar muestra . ' . $response['msg']);
        //              return redirect()->back()->withInput();
        //          }
        //      }
        //  }



        /* Guarda el caso sospecha */

        $patient->suspectCases()->save($suspectCase);

        if($patient->demographic) {
            $patient->demographic->fill($request->all());
            $patient->demographic->save();
        }
        else {
            $demographic = new Demographic($request->All());
            $demographic->patient_id = $patient->id;
            $demographic->save();
        }

        /*Guarda Examen Rápido*/
        if($request->input('value_test')) {
            $rapidtest = new RapidTest($request->All());
            $rapidtest->patient_id = $patient->id;
            $rapidtest->type = "Antígeno";
            $rapidtest->save();
        }


        /* Webservice minsal */
        /* Si se crea el caso por alguien con laboratorio asignado */
        /* La muestra se crea y recepciona inmediatamente en minsal */
        if (env('ACTIVA_WS', false) == true) {
            if($suspectCase->laboratory_id != null) {
                if ($suspectCase->laboratory->minsal_ws == true) {
                    //####### crea muestra en webservice ########
                    $response = WSMinsal::crea_muestra_v2($suspectCase);
                    $ws_minsal_id = $response['msg'];
                    if ($response['status'] == 0) {
                        session()->flash('warninig', 'Error al subir muestra a MINSAL. ' . $response['msg']);
                        $suspectCase->forceDelete();
                        return redirect()->back()->withInput();
                    }

                    $suspectCase->minsal_ws_id = $ws_minsal_id;
                    $suspectCase->save();

                    session()->flash('success', 'Se ha creado el caso número: <h3>'. $suspectCase->id. ' <a href="' . route('lab.suspect_cases.notificationFormSmall',$suspectCase)
                        . '">Imprimir Formulario</a></h3><br />Se ha creado muestra en PNTM. Id generado: ' .$ws_minsal_id);

                    return redirect()->back();
                }
            }
        }



        session()->flash('success', 'Se ha creado el caso número: <h3>'
            . $suspectCase->id. ' <a href="' . route('lab.suspect_cases.notificationFormSmall',$suspectCase)
            . '">Imprimir Formulario</a></h3>');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function show(SuspectCase $suspectCase)
    {
        return view('lab.suspect_cases.show', compact('suspectCase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function edit(SuspectCase $suspectCase)
    {
        //TODO solo debe ser withtrashed si ya existe un registro que ya viene con un lab que se haya eliminado, para poder editar
        $laboratories = Laboratory::withTrashed()->get();

        $establishments = Establishment::whereIn('commune_id',explode(',',env('COMUNAS')))
                                        ->orderBy('name','ASC')->get();

        $sampleOrigins = SampleOrigin::orderBy('alias')->get();

        return view('lab.suspect_cases.edit',
            compact('suspectCase','establishments','sampleOrigins', 'laboratories')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuspectCase $suspectCase)
    {
        $old_pcr = $suspectCase->pcr_sars_cov_2;
        $old_external_laboratory = $suspectCase->external_laboratory;

        $suspectCase->fill($request->all());

        $suspectCase->epidemiological_week = Carbon::createFromDate(
            $suspectCase->sample_at->format('Y-m-d'))->add(1, 'days')->weekOfYear;

        /* Setar el validador */
        if ($old_pcr == 'pending' and $suspectCase->pcr_sars_cov_2 != 'pending') {
            $suspectCase->validator_id = Auth::id();
        }

        if ($request->hasFile('forfile')) {
            $file = $request->file('forfile');
            $file->storeAs('suspect_cases', $suspectCase->id . '.pdf');
            $suspectCase->file = true;
        }



        if(Auth::user()->can('SuspectCase: reception')){
            if ($request->laboratory_id == null) {
            $suspectCase->receptor_id = null;
            $suspectCase->reception_at = null;
            $suspectCase->laboratory_id = null;
        }
        }

        if ($old_pcr == 'pending' && ($suspectCase->pcr_sars_cov_2 == 'positive' || $suspectCase->pcr_sars_cov_2 == 'negative' ||
                $suspectCase->pcr_sars_cov_2 == 'undetermined' || $suspectCase->pcr_sars_cov_2 == 'rejected')
            && $suspectCase->patient->demographic != NULL) {

            $suspectCase->pcr_result_added_at = Carbon::now();

        }

        $suspectCase->save();
        /* Webservice minsal */
        //##### se genera envio de resultado a ws minsal #####

        if (env('ACTIVA_WS', false) == true) {
            if ($suspectCase->laboratory_id != null) {
                if ($suspectCase->laboratory->minsal_ws == true) {
                    if ($suspectCase->minsal_ws_id) {
                        //obtiene id laboratorio external_laboratory
                        $external_laboratory = Laboratory::where('name', $suspectCase->external_laboratory)->first();

                        // //caso1
                        //todo eliminar caso 1? ya que se hace la derivacion en otro formulario
                        if ($old_external_laboratory == NULL && $suspectCase->external_laboratory != null) {
                            //envío información minsal
                            if ($external_laboratory->id_openagora != null) {

//                             $response = WSMinsal::devolver_muestra($suspectCase);
//                             if ($response['status'] == 0) {
//                                 session()->flash('info', 'Error al intentar devolver estado de la muestra (de recepcionada a creada).' . $suspectCase->id . ' en MINSAL. ' . $response['msg']);
//                                 // $suspectCase->result_ifd = "No solicitado";
//                                 $suspectCase->pcr_sars_cov_2_at = NULL;
//                                 $suspectCase->pcr_sars_cov_2 = 'pending';
//                                 $suspectCase->validator_id = NULL;
//                                 $suspectCase->file = NULL;
//                                 $suspectCase->save();
//                                 // return redirect()->route('lab.suspect_cases.index',$suspectCase->laboratory_id);
//                                 return redirect()->back()->withInput();
//                             }

                                $response = WSMinsal::cambia_laboratorio($suspectCase, $external_laboratory->id_openagora);
                                if ($response['status'] == 0) {
                                    session()->flash('warning', 'Error al intentar cambiar laboratorio de la muestra ' . $suspectCase->id . ' en MINSAL. ' . $response['msg']);
                                    $this->suspectCaseBackToPending($suspectCase);
                                    return redirect()->back()->withInput();
                                }
                                session()->flash('success', 'Se cambió laboratorio de la muestra en PNTM');

                            } else {
                                session()->flash('warning', 'No es posible modificar laboratorio en PNTM. No existe *id PNTM* del laboratorio.');
                                $this->suspectCaseBackToPending($suspectCase);
                                return redirect()->back()->withInput();
                            }
                        }

                        //caso4: /si se cambia laboratorio, if $old_external_laboratory != $suspectCase->external_laboratory entra a condición
                        //no es posible, porque no existe webservice. Se debe hacer de forma manual.

                        //caso2
                        if ($old_pcr == 'pending' && ($suspectCase->pcr_sars_cov_2 == 'positive' || $suspectCase->pcr_sars_cov_2 == 'negative' ||
                                $suspectCase->pcr_sars_cov_2 == 'undetermined' || $suspectCase->pcr_sars_cov_2 == 'rejected')
                            && $suspectCase->patient->demographic != NULL && $suspectCase->external_laboratory == null) {

                            //envío información minsal
                            $response = WSMinsal::resultado_muestra($suspectCase);
                            if ($response['status'] == 0) {
                                //Verificar si en pntm esta en estado 2 (no recepcionado) se recepciona.
                                $responseSampleStatus = WSMinsal::obtiene_estado_muestra($suspectCase);
                                if($responseSampleStatus['status'] == 1 && $responseSampleStatus['sample_status'] == 2 && $suspectCase->reception_at != NULL){
                                        $responseReception = WSMinsal::recepciona_muestra($suspectCase);
                                        if($responseReception['status'] == 1){
                                            $response = WSMinsal::resultado_muestra($suspectCase);
                                            if ($response['status'] == 0) {
                                                session()->flash('warning', 'Error al recepcionar y subir resultado de muestra ' . $suspectCase->id . ' en MINSAL. ' . $response['msg']);
                                                $this->suspectCaseBackToPending($suspectCase);
                                                return redirect()->back()->withInput();
                                            }
                                        }else{
                                            session()->flash('warning', 'Error al recepcionar y subir resultado de muestra ' . $suspectCase->id . ' en MINSAL. ' . $response['msg']);
                                            $this->suspectCaseBackToPending($suspectCase);
                                            return redirect()->back()->withInput();
                                        }
                                //Si en PNTM está con resultado y no es el mismo que se intenta cargar, se devuelve a pending
                                }elseif ($responseSampleStatus['status'] == 1 && $responseSampleStatus['sample_status'] == 4 && $suspectCase->pcr_sars_cov_2 != 'pending'){
                                    if ($responseSampleStatus['sample_result'] != $suspectCase->covid19) {
                                        session()->flash('warning', 'Ya existe muestra en PNTM y el resultado es diferente al especificado ' . $suspectCase->id . ' en MINSAL. ' . $response['msg']);
                                        $this->suspectCaseBackToPending($suspectCase);
                                        return redirect()->back()->withInput();
                                    }
                                }
                                else{
                                    session()->flash('warning', 'Error al subir resultado de muestra ' . $suspectCase->id . ' en MINSAL. ' . $response['msg']);
                                    $this->suspectCaseBackToPending($suspectCase);
                                    return redirect()->back()->withInput();
                                }

                            }

                            session()->flash('success', 'Se subió resultado a PNTM');
                        }

                        //caso3
                        //verificar si se cambia resultado de caso, en ese caso se debe modificar dato en tomademuestra.class
                        //si $old_pcr != new_pcr && $old_pcr != 'pending' => está editado, y debiese entrar
                        //no es posible, porque no existe webservice. Se debe hacer de forma manual.
                    }
                }
            }
        }

        /* Crea un TRACING si el resultado es positivo o indeterminado */
        if ($old_pcr == 'pending' and ($suspectCase->pcr_sars_cov_2 == 'positive' OR $suspectCase->pcr_sars_cov_2 == 'undetermined')) {
            /* Si el paciente no tiene Tracing */
            if($suspectCase->patient->tracing) {
                $suspectCase->patient->tracing->index = 1;
                $suspectCase->patient->tracing->status = ($suspectCase->patient->status == 'Fallecido') ? 0:1;
                $suspectCase->patient->tracing->quarantine_start_at = ($suspectCase->symptoms_at) ?
                                                $suspectCase->symptoms_at :
                                                $suspectCase->pcr_sars_cov_2_at;
                $suspectCase->patient->tracing->quarantine_end_at = $suspectCase->patient->tracing->quarantine_start_at->add(13,'days');
                $suspectCase->patient->tracing->next_control_at   = now();
                $suspectCase->patient->tracing->save();
            }
            else {
                $tracing                    = new Tracing();
                $tracing->patient_id        = $suspectCase->patient_id;
                $tracing->user_id           = $suspectCase->user_id;
                $tracing->index             = 1;
                $tracing->establishment_id  = $suspectCase->establishment_id;
                $tracing->functionary       = $suspectCase->functionary;
                $tracing->gestation         = $suspectCase->gestation;
                $tracing->gestation_week    = $suspectCase->gestation_week;
                $tracing->next_control_at   = now(); //$suspectCase->pcr_sars_cov_2_at;
                $tracing->quarantine_start_at = ($suspectCase->symptoms_at) ?
                                                $suspectCase->symptoms_at :
                                                $suspectCase->pcr_sars_cov_2_at;
                $tracing->quarantine_end_at = $tracing->quarantine_start_at->add(13,'days');
                $tracing->observations      = $suspectCase->observation;
                $tracing->notification_at   = $suspectCase->notification_at;
                $tracing->notification_mechanism = $suspectCase->notification_mechanism;
                $tracing->discharged_at     = $suspectCase->discharged_at;
                $tracing->symptoms_start_at = $suspectCase->symptoms_at;
//                switch ($suspectCase->symptoms) {
//                    case 'Si': $tracing->symptoms = 1; break;
//                    case 'No': $tracing->symptoms = 0; break;
//                    default:   $tracing->symptoms = null; break;
//                }
                $tracing->symptoms = $suspectCase->symptoms;
                $tracing->status            = ($suspectCase->patient->status == 'Fallecido') ? 0:1;
                $tracing->save();
            }
        }

        if (env('APP_ENV') == 'production') {
            if ($old_pcr == 'pending' and $suspectCase->pcr_sars_cov_2 == 'positive') {
                $emails  = explode(',', env('EMAILS_ALERT'));
                $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
                Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($suspectCase));
            }

            /* Enviar resultado al usuario, solo si tiene registrado un correo electronico */
            if($old_pcr == 'pending' && ($suspectCase->pcr_sars_cov_2 == 'negative' || $suspectCase->pcr_sars_cov_2 == 'undetermined' ||
                                          $suspectCase->pcr_sars_cov_2 == 'rejected' || $suspectCase->pcr_sars_cov_2 == 'positive')
                                      && $suspectCase->patient->demographic != NULL){
                if($suspectCase->patient->demographic->email != NULL){
                    $email  = $suspectCase->patient->demographic->email;
                    /*PDF SI ES DE */
                    if ($suspectCase->laboratory) {
                        if ($suspectCase->laboratory->pdf_generate == 1) {
                            $case = $suspectCase;
                            $pdf = \PDF::loadView('lab.results.result', compact('case'));
                            $message = new NewNegative($suspectCase);
                            $message->attachData($pdf->output(), $suspectCase->id.'.pdf');
                            Mail::to($email)->send($message);
                        }
                        else{
                          if($suspectCase->file == 1){
                              // $exists = Storage::disk('local')->exists('suspect_cases/'.$suspectCase->id.'.pdf');
                              // dd($exists);

                              $message = new NewNegative($suspectCase);
                              $message->attachFromStorage('suspect_cases/'.$suspectCase->id.'.pdf', $suspectCase->id.'.pdf', [
                                          'mime' => 'application/pdf',
                                        ]);
                              Mail::to($email)->send($message);

                          }
                          else{
                              $message = new NewNegative($suspectCase);
                              Mail::to($email)->send($message);
                          }
                        }
                    }

                }
            }
        }


        if ($request->input('candidate_for_sq') == 1) {
            $sequencingCriteria = new SequencingCriteria();
            $sequencingCriteria->suspect_case_id = $suspectCase->id;
            $sequencingCriteria->save();

        }

        return redirect($request->input('referer'));
        //return redirect()->route('lab.suspect_cases.index',$suspectCase->laboratory_id);
    }

    /**
     * Devuelve el suspect case a su estado 'pending'
     * @param SuspectCase $suspectCase
     */
    private function suspectCaseBackToPending(SuspectCase $suspectCase): void
    {
        $suspectCase->pcr_sars_cov_2_at = NULL;
        $suspectCase->pcr_sars_cov_2 = 'pending';
        $suspectCase->validator_id = NULL;
        $suspectCase->file = NULL;
        $suspectCase->pcr_result_added_at = NULL;
        $suspectCase->save();
    }

    /**
     * Modifica datos notificación de suspect case.
     *
     * @param  \App\request  $request
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function updateNotification(Request $request, SuspectCase $suspectCase){
        $selected_establishment = $request->selected_establishment;

        if($request->notification_at != null && $request->notification_mechanism != null){
            $suspectCase->notification_at = $request->notification_at;
            $suspectCase->notification_mechanism = $request->notification_mechanism;
            $suspectCase->save();

            session()->flash('success', 'Se ha ingresado la notificación');
        }else{
            session()->flash('warning', 'Debe seleccionar ambos parámetros');
        }

        return redirect()->back()->with(compact('selected_establishment'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuspectCase $suspectCase)
    {
        $suspectCase->delete();

        return redirect()->route('lab.suspect_cases.index');
    }

    public function fileDelete(SuspectCase $suspectCase)
    {
        /* TODO: implementar auditable en file delete  */
        if (Storage::delete( 'suspect_cases/' . $suspectCase->id . '.pdf')){
            $suspectCase->file = false;
            $suspectCase->save();
            session()->flash('info', 'Se ha eliminado el archivo correctamente.');
        }

        return redirect()->back();
    }


    /**
     * Search suspectCase by ID.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function search_id(Request $request)
    {
        $suspectCase = SuspectCase::find($request->input('id'));
        if($suspectCase) return redirect()->route('lab.suspect_cases.edit', $suspectCase);
        else {
            session()->flash('warning', 'No se ha encontrado el exámen ID: <h3>' . $request->input('id') . '</h3>');
            return redirect()->back();
        }
    }


    public function historical_report(Request $request)
    {
        if($request->has('date')){
            $date = $request->get('date');
        } else {
            $date = Carbon::now();
        }

        $reportBackup = ReportBackup::whereDate('created_at',$date)->get();

        if($reportBackup->count() <> 0){
            if($reportBackup->first()->id <= 10){
                $html = json_decode($reportBackup->first()->data, true);
            }else{
                $html = $reportBackup->first()->data;
            }

            $begin = strpos($html, '<main class="py-4 container">')+29;
            $v1 = substr($html, $begin, 999999);
            $end   = strpos($v1, '</main>')-7;
            $main = substr($v1, 0, $end);

            $begin = strpos($html, '<head>')+6;
            $v1 = substr($html, $begin, 999999);
            $end   = strpos($v1, '</head>');
            $head = substr($v1, 0, $end);
        }else{
            $head="";
            $main="";
        }

        return view('lab.suspect_cases.reports.historical_report', compact('head','main','date'));
    }

    public function diary_by_lab_report(Request $request)
    {
        $start = microtime(true);

        if (SuspectCase::count() == 0){
            session()->flash('info', 'No existen casos.');
            return redirect()->route('home');
        }

        //FIRST CASE
        $beginExamDate = SuspectCase::orderBy('sample_at')->first()->sample_at;
        $laboratories = Laboratory::withTrashed()->get();

        $periods = CarbonPeriod::create($beginExamDate, now()->addDay());
        $periods_count = $periods->count();

        // NUEVO CODIGO
        foreach ($periods as $key => $period) {
            foreach ($laboratories as $lab) {
          		$cases_by_days[$period->format('d-m-Y')]['laboratories'][$lab->name] = 0;
          	}
            $cases_by_days[$period->format('d-m-Y')]['cases'] = 0;
        }

        $total_cases_by_days['cases'] = $suspectCases = SuspectCase::count();

        $suspectCases = SuspectCase::selectRaw('DATE(pcr_sars_cov_2_at) as pcr_sars_cov_2_at, external_laboratory, count(*) as cantidad')
                                    // ->addSelect('external_laboratory')
                                    ->whereNotNull('pcr_sars_cov_2_at')
                                    ->wherenotnull('external_laboratory')
                                    ->groupBy('pcr_sars_cov_2_at','external_laboratory')
                                    ->get();

        foreach ($suspectCases as $suspectCase) {
            $cases_by_days[$suspectCase->pcr_sars_cov_2_at->format('d-m-Y')]['laboratories'][$suspectCase->external_laboratory] += $suspectCase->cantidad;
            $cases_by_days[$suspectCase->pcr_sars_cov_2_at->format('d-m-Y')]['cases'] += $suspectCase->cantidad;
            $total_cases_by_days['cases'] += $suspectCase->cantidad;
        }

        $suspectCases = SuspectCase::select('laboratories.name',\DB::raw('DATE_FORMAT(suspect_cases.pcr_sars_cov_2_at, "%d-%m-%Y") as pcr_sars_cov_2_at, count(*) as cantidad'))
                                    ->leftJoin('laboratories', 'laboratories.id', '=', 'suspect_cases.laboratory_id')
                                    ->groupBy('laboratories.name',\DB::raw('DATE_FORMAT(suspect_cases.pcr_sars_cov_2_at, "%d-%m-%Y")'))
                                    ->whereNotNull('pcr_sars_cov_2_at')
                                    ->whereNotNull('laboratories.name')
                                    ->whereNull('external_laboratory')
                                    ->get();

        //CARGA ARRAY CASOS
        foreach ($suspectCases as $suspectCase) {
            $cases_by_days[$suspectCase->pcr_sars_cov_2_at->format('d-m-Y')]['laboratories'][$suspectCase->name] += $suspectCase->cantidad;
            $cases_by_days[$suspectCase->pcr_sars_cov_2_at->format('d-m-Y')]['cases'] += $suspectCase->cantidad;
            $total_cases_by_days['cases'] += $suspectCase->cantidad;
        }

        return view('lab.suspect_cases.reports.diary_by_lab_report', compact('cases_by_days', 'total_cases_by_days'));
    }

    public function diary_lab_report(Request $request)
    {

        if (SuspectCase::count() == 0){
            session()->flash('info', 'No existen casos.');
            return redirect()->route('home');
        }

        $beginExamDate = SuspectCase::orderBy('sample_at')->first()->sample_at;

        $periods = CarbonPeriod::create($beginExamDate, now()->addDay());

        $periods_count = $periods->count();

        foreach ($periods as $key => $period) {
            $cases_by_days[$period->format('d-m-Y')]['cases'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['negative'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['positive'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['rejected'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['undetermined'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['pending'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['procesing'] = 0;

        }

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $communes = Commune::whereIn('id',$env_communes)->orderBy('name','ASC')->get();
        $suspectCases = SuspectCase::whereNotNull('laboratory_id')
                                    ->whereHas('patient', function($q) use ($env_communes){
                                            $q->whereHas('demographic', function($q) use ($env_communes){
                                                    $q->whereIn('commune_id',$env_communes);
                                            });
                                     })
                                    ->get();

        if ($suspectCases->count() == 0){
            session()->flash('info', 'No existen casos con laboratorio.');
            return redirect()->route('home');
        }

        foreach ($suspectCases as $suspectCase) {
          $total_cases_by_days['cases'] = 0;
          $total_cases_by_days[$suspectCase->pcr_sars_cov_2] = 0;
        }



        //CARGA ARRAY CASOS
        foreach ($suspectCases as $suspectCase) {

          $cases_by_days[$suspectCase->sample_at->format('d-m-Y')]['cases'] += 1;
          if($suspectCase->reception_at != null){
            $cases_by_days[$suspectCase->sample_at->format('d-m-Y')][$suspectCase->pcr_sars_cov_2] += 1;
          }
          if($suspectCase->pcr_sars_cov_2_at != null){
            $cases_by_days[$suspectCase->pcr_sars_cov_2_at->format('d-m-Y')]['procesing'] += 1;
          }

          $total_cases_by_days['cases'] += 1;
          $total_cases_by_days[$suspectCase->pcr_sars_cov_2] += 1;
        }

        return view('lab.suspect_cases.reports.diary_lab_report', compact('cases_by_days', 'total_cases_by_days'));
    }

    public function positive_average_by_commune(Request $request)
    {
        // FIX TIEMPO LIMITE DE EJECUCUCION Y MEMORIA LIMITE EN PHP.INI
          set_time_limit(3600);
          ini_set('memory_limit', '1024M');

        if (SuspectCase::count() == 0){
            session()->flash('info', 'No existen casos.');
            return redirect()->route('home');
        }

        $from = Carbon::now()->subDays(30);
        $to = Carbon::now();

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $communes = Commune::whereIn('id',$env_communes)->orderBy('name','ASC')->get();

        $beginExamDate = SuspectCase::whereBetween('sample_at',[$from,$to])
                                    ->whereNotNull('laboratory_id')
                                    ->whereHas('patient', function($q) use ($env_communes){
                                            $q->whereHas('demographic', function($q) use ($env_communes){
                                                    $q->whereIn('commune_id',$env_communes);
                                            });
                                     })
                                    ->orderBy('sample_at')
                                    ->first()->sample_at;

        $periods = CarbonPeriod::create($beginExamDate, now());

        foreach ($communes as $key => $commune) {
            foreach ($periods as $key => $period) {
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['total'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['total'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['total'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['total'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['total'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['total'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['total'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['positivos'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['positivos'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['positivos'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['positivos'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['positivos'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['positivos'] = 0;
                $cases_by_days[$period->format('d-m-Y')][$commune->name]['positivos'] = 0;
            }
            $cases_by_days[$to->format('d-m-Y')][$commune->name]['total'] = 0;
            $cases_by_days[$to->format('d-m-Y')][$commune->name]['positivos'] = 0;
        }

        $suspectCases = SuspectCase::whereBetween('sample_at',[$from,$to])
                                   ->whereNotNull('laboratory_id')
                                   // ->where('pcr_sars_cov_2','positive')
                                   ->whereHas('patient', function($q) use ($env_communes){
                                           $q->whereHas('demographic', function($q) use ($env_communes){
                                                   $q->whereIn('commune_id',$env_communes);
                                           });
                                    })
                                   ->get();

        if ($suspectCases->count() == 0){
            session()->flash('info', 'No existen casos con laboratorio.');
            return redirect()->route('home');
        }

        //CARGA ARRAY CASOS
        foreach ($suspectCases as $suspectCase) {
            //total
            if ($communes->contains('name',$suspectCase->patient->demographic->commune->name)) {
                $cases_by_days[$suspectCase->sample_at->format('d-m-Y')][$suspectCase->patient->demographic->commune->name]['total'] += 1;
            }

            //positivos
            if ($suspectCase->pcr_sars_cov_2 == "positive") {
                if ($communes->contains('name',$suspectCase->patient->demographic->commune->name)) {
                    $cases_by_days[$suspectCase->sample_at->format('d-m-Y')][$suspectCase->patient->demographic->commune->name]['positivos'] += 1;
                }
            }
        }

        return view('lab.suspect_cases.reports.positive_average_by_commune', compact('cases_by_days'));
    }

//    public function estadistico_diario_covid19(Request $request)
//    {
//        $yesterday = Carbon::now()->subDays(1)->format('Y-m-d 21:00');
//        $now = Carbon::now()->format('Y-m-d 21:00');
//        //dd($yesterday, $now);
//
//        $array = array();
//        $cases = SuspectCase::whereBetween('created_at',[$yesterday,$now])
//                            ->where('external_laboratory',NULL)
//                            ->whereNotNull('laboratory_id')
//                            ->get();
//        //dd($cases);
//        foreach ($cases as $key => $case) {
//          $array[$case->laboratory->name]['muestras_en_espera'] = 0;
//          $array[$case->laboratory->name]['muestras_recibidas'] = 0;
//          $array[$case->laboratory->name]['muestras_procesadas'] = 0;
//          $array[$case->laboratory->name]['muestras_positivas'] = 0;
//          $array[$case->laboratory->name]['muestras_procesadas_acumulados'] = 0;
//          $array[$case->laboratory->name]['muestras_procesadas_positivo'] = 0;
//          $array[$case->laboratory->name]['commune'] = '';
//        }
//
//        foreach ($cases as $key => $case) {
//          if($case->pcr_sars_cov_2 == "pending"){
//            $array[$case->laboratory->name]['muestras_en_espera'] += 1;
//          }
//          $array[$case->laboratory->name]['muestras_recibidas'] += 1;
//          if($case->pcr_sars_cov_2 != "pending" || $case->pcr_sars_cov_2 != "rejected"){
//            $array[$case->laboratory->name]['muestras_procesadas'] += 1;
//          }
//          if($case->pcr_sars_cov_2 == "positive"){
//            $array[$case->laboratory->name]['muestras_positivas'] += 1;
//          }
//
//          $array[$case->laboratory->name]['muestras_procesadas_acumulados'] = SuspectCase::where('external_laboratory',NULL)
//                                                                                         ->where('laboratory_id',$case->laboratory_id)
//                                                                                         ->where('pcr_sars_cov_2','<>','pending')
//                                                                                         ->where('pcr_sars_cov_2','<>','rejected')
//                                                                                         ->count();
//
//          $array[$case->laboratory->name]['muestras_procesadas_positivo'] = SuspectCase::where('external_laboratory',NULL)
//                                                                                         ->where('laboratory_id',$case->laboratory_id)
//                                                                                         ->where('pcr_sars_cov_2','positive')
//                                                                                         ->count();
//          $array[$case->laboratory->name]['commune'] = $case->laboratory->commune->name;
//        }
//
//        //dd($array);
//
//        return view('lab.suspect_cases.reports.estadistico_diario_covid19', compact('array','yesterday', 'now'));
//    }



    public function download(SuspectCase $suspectCase)
    {
        return Storage::response( 'suspect_cases/' . $suspectCase->id . '.pdf', mb_convert_encoding($suspectCase->id . '.pdf', 'ASCII'));
    }

    public function login($access_token = null)
    {
        if ($access_token) {
            return redirect()->route('lab.result')->with('access_token', $access_token);
        }
    }

    public function result()
    {
      // dd("");
        if (env('APP_ENV') == 'production') {
            $access_token = session()->get('access_token');
            $url_base = "https://www.claveunica.gob.cl/openid/userinfo/";
            $response = Http::withToken($access_token)->post($url_base);
            $user_cu = json_decode($response);

            $user = new User();
            $user->id = $user_cu->RolUnico->numero;
            $user->dv = $user_cu->RolUnico->DV;
            $user->name = implode(' ', $user_cu->name->nombres);
            $user->fathers_family = $user_cu->name->apellidos[0];
            $user->mothers_family = $user_cu->name->apellidos[1];
            $user->email = $user_cu->email;
        } elseif (env('APP_ENV') == 'local') {
            $user = new User();
            $user->id = 16055586;
            $user->dv = 6;
            $user->name = "maria angela";
            $user->fathers_family = "family";
            $user->mothers_family = "mother";
            $user->email = "email@email.com";
        }

        // dd($user);

        Auth::login($user);
        $patient = Patient::where('run', $user->id)->first();
        return view('lab.result', compact('patient'));
    }

    public function print(SuspectCase $suspect_case)
    {
        //$case = SuspectCase::find(1);
        $case = $suspect_case;

        $pdf = \PDF::loadView('lab.results.result', compact('case'));
        return $pdf->stream();
    }


    public function printpost(SuspectCase $suspect_case)
    {
        //$case = SuspectCase::find(1);
        $case = $suspect_case;

        $pdf = \PDF::loadView('lab.results.result', compact('case'));
        return $pdf->stream();
    }


    public function reception_inbox(Request $request)
    {
        $selectedEstablishment = $request->input('establishment_id');
        $nameFilter = $request->input('filter_name_string');
        $idFilter = $request->input('search');

        $suspectCases = SuspectCase::search($idFilter)
            ->where(function($q) use($selectedEstablishment){
                if($selectedEstablishment){
                    $q->where('establishment_id', $selectedEstablishment);
                }
            })
            ->where(function ($query){
                $query->where('laboratory_id', Auth::user()->laboratory_id)
                    ->orWhereNull('laboratory_id');
            })
            ->wherehas('patient', function ($q) use ($nameFilter) {
                $q->search($nameFilter);
            })
            ->where('reception_at', NULL)
            ->latest()
            ->paginate(200);

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();
        $laboratories = Laboratory::all();

        return view('lab.suspect_cases.reception_inbox', compact('suspectCases', 'establishments', 'selectedEstablishment', 'laboratories', 'nameFilter', 'idFilter'));
    }

    /**
     * Muestra index de recepción por código de barras
     * @param Request $request
     * @return Application|Factory|View
     */
    public function barcodeReceptionIndex(Request $request)
    {
        return view('lab.suspect_cases.reception_barcode');
    }

    /**
     * Recepciona por id de caso mediante código de barras
     * @param Request $request
     * @return Application|Factory|View
     */
    public function barcodeReception(Request $request){
        if($request->has('id')){
            $suspectCase = SuspectCase::find($request->get('id'));
            $arraySuspectCase['id'] = $suspectCase->id;
            $arraySuspectCase['fullName'] = $suspectCase->patient->fullName;
            $arraySuspectCase['sampleAt'] = $suspectCase->sample_at;
        }


        if(!$suspectCase){
            session()->flash('warning', "No se encuentra muestra con la id $request->get('id'.");
        }elseif ($suspectCase->reception_at == NULL){
            $receptionSuccess = $this->reception($request, $suspectCase, true);

            if ($receptionSuccess) {
                if(!session()->has('suspect_cases.received')){
                    session()->put('suspect_cases.received', array());
                }
                session()->push('suspect_cases.received', $arraySuspectCase);
            }

        }else{
            session()->flash('warning', "La muestra $suspectCase->id ya se encuentra recepcionada");
        }

        return view('lab.suspect_cases.reception_barcode', compact('suspectCase'));
    }

    /**
     * Borra de la sesión los casos recepcionados para imprimir
     */
    public function barcodeReceptionForgetCasesReceived()
    {
        session()->forget('suspect_cases.received');
        return view('lab.suspect_cases.reception_barcode');

    }

//    public function exportExcel($cod_lab){
////    public function exportExcel(Request $request, $cod_lab){
//
//        //        return Excel::download(new SuspectCasesExport($cod_lab, $request->get('date_filter')), 'lista-examenes.xlsx');
//        return Excel::download(new SuspectCasesExport($cod_lab), 'lista-examenes.xlsx');
//    }

    public function exportExcel($cod_lab, $date = null){
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=lista-examenes.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filas = null;
        if ($cod_lab == 'own') {
            $filas = SuspectCase::where(function ($q) {
                $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
                    ->orWhere('user_id', Auth::user()->id);
            })->orderBy('suspect_cases.id', 'desc')->get();

        } elseif ($cod_lab == 'all') {
            $month = Carbon::parse($date)->month;
            $year = Carbon::parse($date)->year;

            $filas = SuspectCase::whereYear('sample_at', '=', $year)
                ->whereMonth('sample_at', '=', $month)
                ->whereNotNull('laboratory_id')
                ->orderBy('suspect_cases.id', 'desc')
                ->get();

        } else {
            $month = Carbon::parse($date)->month;
            $year = Carbon::parse($date)->year;

            $filas = SuspectCase::where('laboratory_id', $cod_lab)
                ->whereYear('sample_at', '=', $year)
                ->whereMonth('sample_at', '=', $month)
                ->orderBy('suspect_cases.id', 'desc')
                ->get();
        }

        $columnas = array(
            '#',
            'fecha_muestra',
            'origen',
            'nombres',
            'apellido paterno',
            'apellido materno',
            'run',
            'fecha de nacimiento',
            'edad',
            'sexo',
            'Laboratorio',
            'resultado_ifd',
            'pcr_sars_cov2',
            'Casos Recuperados (Nueva Muestra)' ,
            'sem',
            'epivigila',
            'fecha de resultado',
            'observación',
            'mecanismo de notificación',
            'fecha de notificación',
            'teléfono',
            'dirección',
            'comuna',
            'país',
            'email',
            'lugar de trabajo',
            'funcionario de salud',
            'fecha envío lab. externo',
            'tipo de caso'
        );

        $callback = function() use ($filas, $columnas)
        {
            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($file, $columnas,';');

            foreach($filas as $fila) {
                fputcsv($file, array(
                    $fila->id,
                    $fila->sample_at,
                    ($fila->establishment)?$fila->establishment->alias.' - '.$fila->origin: '',
                    // ($fila->patient)?$fila->patient->fullName:'',
                    ($fila->patient)?$fila->patient->name:'',
                    ($fila->patient)?$fila->patient->fathers_family:'',
                    ($fila->patient)?$fila->patient->mothers_family:'',
                    ($fila->patient)?$fila->patient->Identifier:'',
                    ($fila->patient && $fila->patient->birthday)?$fila->patient->birthday->format('d-m-Y'):'',
                    $fila->age,
                    strtoupper($fila->gender[0]),
                    $fila->laboratory->name,
                    $fila->result_ifd,
                    $fila->Covid19,
                    $fila->positive_condition,
                    $fila->epidemiological_week,
                    $fila->epivigila,
                    $fila->pcr_sars_cov_2_at,
                    $fila->observation,
                    $fila->notification_mechanism,
                    ($fila->notification_at)?$fila->notification_at->format('d-m-Y'):'',
                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->telephone:'',
                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->fullAddress:'',
                    ($fila->patient && $fila->patient->demographic && $fila->patient->demographic->commune)?$fila->patient->demographic->commune->name:'',
                    ($fila->patient && $fila->patient->demographic && $fila->patient->demographic->nationality) ? $fila->patient->demographic->nationality : '',
                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->email:'',
                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->workplace:'',
                    ($fila->functionaryEsp)?$fila->functionaryEsp:'',
                    $fila->sent_external_lab_at,
                    $fila->case_type
                ),';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportMinsalExcel($laboratory, Request $request)
    {
        if($from = $request->has('from')){
            $from = $request->get('from');
            $to = $request->get('to');
        }else{
            $from = date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
            $to = date("Y-m-d 20:59:59");
        }

        return Excel::download(new MinsalSuspectCasesExport($laboratory, $from, $to), 'reporte-minsal-desde-'.$from.'-hasta-'.$to.'.xlsx');
    }

    public function exportSeremiExcel($cod_lab = null)
    {
        return Excel::download(new SeremiSuspectCasesExport($cod_lab), 'reporte-seremi.xlsx');
    }

    public function notificationForm(SuspectCase $suspectCase)
    {
        $user = auth()->user();
        return view('lab.suspect_cases.notification_form', compact('suspectCase', 'user'));
    }

    public function notificationFormSmall(SuspectCase $suspectCase){
        $user = auth()->user();
        return view('lab.suspect_cases.notification_form_small', compact('suspectCase', 'user'));
    }

    public function notificationFormSmallBulk(Request $request){
        if ($request->has('selected_cases_ids')) {
            $selectedCasesIds = $request->get('selected_cases_ids');
            $suspectCases = SuspectCase::whereIn('id', $selectedCasesIds)->get();
        }

        return view('lab.suspect_cases.notification_form_small_bulk', compact('suspectCases'));
    }

    public function exportExcelReceptionInbox($cod_lab){
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=examenes_pendientes_recepcionar.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

//        ->where(function ($query){
//            $query->where('laboratory_id', Auth::user()->laboratory_id)
//                ->orWhereNull('laboratory_id');
//        })

        $filas = null;
        $filas = SuspectCase::where('laboratory_id', Auth::user()->laboratory_id)
            ->where('reception_at', NULL)
            ->latest()
            ->get();


        $columnas = array(
            '#',
            'fecha_muestra',
            'establecimiento',
            'estab. detalle',
            'nombre',
            'identificador',
            'edad',
            'sexo',
            'pcr_sars-cov2',
            'observación',
            'fecha_nacimiento',
            'nacionalidad',
            'correo_electronico',
            'region_toma_muestra',
            'trabajador_de_la_salud',
            'contacto_estrecho',
            'gestante',
            'semanas_gestacion',
            'presenta_sintomatología',
            'fecha_inicio_síntomas',
            'teléfono'
//            'sem',
//            'epivigila',
//            'fecha de resultado',
//            'teléfono',
//            'dirección',
//            'comuna'
        );

        $callback = function() use ($filas, $columnas)
        {
            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($file, $columnas,';');
            foreach($filas as $fila) {
                fputcsv($file, array(
                    $fila->id,
                    $fila->sample_at,
                    ($fila->establishment)?$fila->establishment->alias: '',
                    $fila->origin,
                    ($fila->patient)?$fila->patient->fullName:'',
                    ($fila->patient)?$fila->patient->Identifier:'',
                    $fila->age,
                    strtoupper($fila->gender[0]),
                    $fila->Covid19,
                    $fila->observation,
                    ($fila->patient) ? $fila->patient->birthday : '',
                    ($fila->patient && $fila->patient->demographic) ? $fila->patient->demographic->nationality : '',
                    ($fila->patient && $fila->patient->demographic) ? $fila->patient->demographic->email : '',
                    'Tarapacá',
                    ($fila->functionary === NULL) ? '' : (($fila->functionary === 1) ? 'Si' : 'No'),
                    ($fila->close_contact === NULL) ? '' : (($fila->close_contact === 1) ? 'Si' : 'No'),
                    ($fila->gestation == 1) ? 'Si' : 'No', //todo
                    $fila->gestation_week,
                    ($fila->symptoms === NULL) ? '' : (($fila->symptoms === 1) ? 'Si' : 'No'),
                    $fila->symptoms_at,
                    ($fila->patient && $fila->patient->demographic) ? $fila->patient->demographic->telephone : ''
//                    $fila->epidemiological_week,
//                    $fila->epivigila,
//                    $fila->pcr_sars_cov_2_at,
//                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->telephone:'',
//                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->fullAddress:'',
//                    ($fila->patient && $fila->patient->demographic && $fila->patient->demographic->commune)?$fila->patient->demographic->commune->name:'',
                ),';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function index_bulk_load(){
        $bulkLoadRecords = BulkLoadRecord::orderBy('id', 'Desc')->get();
        return view('lab.bulk_load.import', compact('bulkLoadRecords'));
    }

    public function index_import_results(){
        return view('lab.suspect_cases.import_results');
    }

    public function bulk_load_import(Request $request){
        set_time_limit(0);
        $file = $request->file('file');
        $startDate = Carbon::now()->subWeeks(4)->setTime(0,0,0,0);
        $endDate = Carbon::now()->setTime(0,0,0,0);

        $patientsCollection = Excel::toCollection(new PatientImport, $file);

        foreach ($patientsCollection[0] as $patient) {

            if($patient['Fecha Resultado'] != null){
                $fecha_resultado_carbon = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['Fecha Resultado']));
                if(!$fecha_resultado_carbon->betweenIncluded($startDate, $endDate)){
                    session()->flash('warning', "La fecha de resultado {$fecha_resultado_carbon->format('d-m-Y')} debe estar entre {$startDate->format('d-m-Y')} y {$endDate->format('d-m-Y')}.");
                    return redirect()->route('lab.bulk_load.index');
                    // return view('lab.bulk_load.index');
                }
            }

            if (ctype_digit($patient['RUN'])) {
                $patientsDB = Patient::where('run', $patient['RUN'])
                    ->orWhere('other_identification', $patient['RUN'])
                    ->get();
            }
            else{
                $patientsDB = Patient::where('other_identification', $patient['RUN'])
                    ->get();
            }

                if($patientsDB->count() == 0){
                    $new_patient = new Patient();
                    if($patient['DV'] != null){
                        $new_patient->run = $patient['RUN'];
                        $new_patient->dv  = $patient['DV'];
                    }
                    else {
                        $new_patient->other_identification  = $patient['RUN'];
                    }

                    $new_patient->name            = $patient['Nombres'];
                    $new_patient->fathers_family  = $patient['Apellido Paterno'];
                    $new_patient->mothers_family  = $patient['Apellido Materno'];

                    if($patient['Sexo'] == 'Masculino'){
                        $new_patient->gender = 'male';
                    }
                    if($patient['Sexo'] == 'Femenino'){
                        $new_patient->gender = 'female';
                    }
                    if($patient['Sexo'] == 'Otro'){
                        $new_patient->gender = 'other';
                    }
                    if($patient['Sexo'] == 'Desconocido'){
                        $new_patient->gender = 'unknown';
                    }

                    $new_patient->birthday        = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['Fecha Nacimiento']))->format('Y-m-d H:i:s');

                    $new_patient->status          = $patient['Estado'];

                    $new_patient->save();
                }

            if (ctype_digit($patient['RUN'])) {
                $patient_create = Patient::where('run', $patient['RUN'])
                    ->orWhere('other_identification', $patient['RUN'])
                    ->get()
                    ->first();
            }
            else{
                $patient_create = Patient::where('other_identification', $patient['RUN'])
                    ->get()
                    ->first();
            }

                if($patient_create){
                  if(!$patient_create->demographic){
                      $new_demographic = new Demographic();

                      $new_demographic->street_type   = $patient['Via Residencia'];
                      $new_demographic->address       = $patient['Direccion'];
                      $new_demographic->number        = $patient['Numero'];
                      $new_demographic->department    = $patient['Depto'];
                      $new_demographic->city          = $patient['Ciudad o Pueblo'];
                      $new_demographic->suburb        = $patient['Poblacion o Suburbio'];
                      $new_demographic->commune_id    = $patient['Comuna'];
                      $new_demographic->region_id     = $patient['Region'];
                      $new_demographic->nationality   = ucfirst(strtolower($patient['Nacionalidad']));
                      $new_demographic->telephone     = $patient['Telefono'];
                      $new_demographic->email         = $patient['Email'];
                      $new_demographic->patient_id    = $patient_create->id;
                      $new_demographic->save();
                  }
                }

                if($patient_create && $patient['Laboratorio'] != null){
                    $new_suspect_case = new SuspectCase();

                    $new_suspect_case->laboratory_id      = $patient['Laboratorio'];
                    $new_suspect_case->sample_type        = strtoupper($patient['Tipo Muestra']);
                    $new_suspect_case->sample_at          = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['Fecha Muestra']))->format('Y-m-d H:i:s');

                    if($patient['Fecha Recepcion'] != null){
                        $new_suspect_case->reception_at       = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['Fecha Recepcion']))->format('Y-m-d H:i:s');
                    }

                    if($patient['Fecha Resultado'] != null){
                        $new_suspect_case->pcr_sars_cov_2_at       = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['Fecha Resultado']))->format('Y-m-d H:i:s');
                    }

                    if($patient['Resultado'] == 'Positivo'){
                        $new_suspect_case->pcr_sars_cov_2 = 'positive';
                    }
                    if($patient['Resultado'] == 'Negativo'){
                        $new_suspect_case->pcr_sars_cov_2 = 'negative';
                    }
                    if($patient['Resultado'] == 'Indeterminado'){
                        $new_suspect_case->pcr_sars_cov_2 = 'undetermined';
                    }
                    if($patient['Resultado'] == 'Rechazado'){
                        $new_suspect_case->pcr_sars_cov_2 = 'rejected';
                    }
                    if($patient['Resultado'] == 'Pendiente'){
                        $new_suspect_case->pcr_sars_cov_2 = 'pending';
                    }

                    $establishment = Establishment::where('name', $patient['Establecimiento Muestra'])
                        ->get()
                        ->first();

                    $new_suspect_case->establishment_id = $establishment['id'];
                    $new_suspect_case->origin = $patient['Detalle Origen'];
                    $new_suspect_case->run_medic = $patient['Run Medico'];

                    // ---------------------------------------------------------------------
                    if($patient['Sintomas'] == 'Si' || $patient['Sintomas'] == 'si' ||
                          $patient['Sintomas'] == 'si' || $patient['Sintomas'] == 'sI' ||
                        $patient['Sintomas'] == 'SI'){
                        $new_suspect_case->symptoms = 1;
                        $new_suspect_case->symptoms_at = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['Fecha Inicio Sintomas']))->format('Y-m-d H:i:s');
                    }
                    if($patient['Sintomas'] == 'No' || $patient['Sintomas'] == 'no' ||
                          $patient['Sintomas'] == 'no' || $patient['Sintomas'] == 'nO' || $patient['Sintomas'] == 'NO'){
                        $new_suspect_case->symptoms = 0;
                    }
                    // ---------------------------------------------------------------------

                    if($patient['Gestante'] == 'Si' || $patient['Gestante'] == 'si' ||
                          $patient['Gestante'] == 'si' || $patient['Gestante'] == 'sI' ||
                        $patient['Gestante'] == 'SI' ){
                        $new_suspect_case->gestation = 1;
                        $new_suspect_case->gestation_week = $patient['Semanas Gestacion'];

                    }
                    if($patient['Gestante'] == 'No' || $patient['Gestante'] == 'no' ||
                          $patient['Gestante'] == 'no' || $patient['Gestante'] == 'nO' ||
                        $patient['Gestante'] == 'NO'){
                        $new_suspect_case->gestation = 0;
                    }
                    // ---------------------------------------------------------------------

                    if($patient['Indice'] == 'Si' || $patient['Indice'] == 'si' ||
                          $patient['Indice'] == 'si' || $patient['Indice'] == 'sI' ||
                        $patient['Indice'] == 'SI'){
                        $new_suspect_case->close_contact = 1;
                    }
                    if($patient['Indice'] == 'No' || $patient['Indice'] == 'no' ||
                          $patient['Indice'] == 'no' || $patient['Indice'] == 'nO' ||
                        $patient['Indice'] == 'NO'){
                        $new_suspect_case->close_contact = 0;
                    }
                    // ---------------------------------------------------------------------

                    if($patient['Funcionario Salud'] == 'Si' || $patient['Funcionario Salud'] == 'si' ||
                          $patient['Funcionario Salud'] == 'si' || $patient['Funcionario Salud'] == 'sI' ||
                        $patient['Funcionario Salud'] == 'SI'){
                        $new_suspect_case->functionary = 1;
                    }
                    if($patient['Funcionario Salud'] == 'No' || $patient['Funcionario Salud'] == 'no' ||
                          $patient['Funcionario Salud'] == 'no' || $patient['Funcionario Salud'] == 'nO' ||
                        $patient['Funcionario Salud'] == 'NO'){
                        $new_suspect_case->functionary = 0;
                    }
                    // ---------------------------------------------------------------------

                    $new_suspect_case->observation = $patient['Observacion'];
                    $new_suspect_case->epivigila = $patient['Epivigila'];
                    $new_suspect_case->patient_id = $patient_create->id;
                    $new_suspect_case->user_id = Auth::user()->id;
                    $new_suspect_case->validator_id = Auth::user()->id;

                    if($patient['Sexo'] == 'Masculino'){
                        $new_suspect_case->gender = 'male';
                    }
                    if($patient['Sexo'] == 'Femenino'){
                        $new_suspect_case->gender = 'female';
                    }
                    if($patient['Sexo'] == 'Otro'){
                        $new_suspect_case->gender = 'other';
                    }
                    if($patient['Sexo'] == 'Desconocido'){
                        $new_suspect_case->gender = 'unknown';
                    }

                    $new_suspect_case->save();
                }
            }

            //AGREGAR EVENTO DE INGRESA QUIEN SOLICITA.
            $bulkLoadRecord = new BulkLoadRecord();
            $bulkLoadRecord->description = $request->description;
            $bulkLoadRecord->user()->associate(Auth::user());
            $bulkLoadRecord->save();

        session()->flash('success', 'El archivo fue cargado exitosamente.');
        return redirect()->route('lab.bulk_load.index');
    }

    public function index_bulk_load_from_pntm(){
        return view('lab.bulk_load_from_pntm.import');
    }

    /**
     * @throws Throwable
     */
    public function bulk_load_import_from_pntm(Request $request){
        set_time_limit(0);
        $timeStart = microtime(true);

        $file = $request->file('file');
        $warningMsg = '';
        $casesInsertedNumber = 0;
        $patientsCollection = Excel::toCollection(new PatientImport, $file);

        foreach ($patientsCollection[0] as $patient) {
            if (SuspectCase::where('minsal_ws_id', '=', $patient['id_muestra'])->exists()) {
                continue;
            }

            DB::beginTransaction();
            try {
                if ($patient['tipo_documento_paciente'] == 'RUN') {
                    if (str_contains($patient['id_paciente'], '-')) {
                        $run = explode('-', $patient['id_paciente']);
                        $dv = $run[1];
                        $run = $run[0];
                    } else {
                        $run = $patient['id_paciente'];
                        $dv = null;
                    }
                } else {
                    $run = $patient['id_paciente'];
                    $dv = null;
                }

                $patientsDB = Patient::where('run', $run)
                    ->orWhere('other_identification', $run)
                    ->get();

                if ($patientsDB->count() == 0) {
                    $new_patient = new Patient();
                    if ($dv != null) {
                        $new_patient->run = $run;
                        $new_patient->dv = $dv;
                    } else {
                        $new_patient->other_identification = $run;
                    }

                    $new_patient->name = $patient['nombre_paciente'];
                    $new_patient->fathers_family = $patient['apellido_paterno_paciente'];
                    $new_patient->mothers_family = $patient['apellido_materno_paciente'];

                    if ($patient['sexo_paciente'] == 'M') {
                        $new_patient->gender = 'male';
                    }
                    if ($patient['sexo_paciente'] == 'F') {
                        $new_patient->gender = 'female';
                    }
                    if ($patient['sexo_paciente'] == 'Intersex') {
                        $new_patient->gender = 'other';
                    }
                    if ($patient['sexo_paciente'] == 'Desconocido') {
                        $new_patient->gender = 'unknown';
                    }

                    $new_patient->birthday = Carbon::parse($patient['fecha_nacimiento_paciente']);
                    //                $new_patient->birthday = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['fecha_nacimiento_paciente']))->format('Y-m-d H:i:s');
                    $new_patient->save();

                } else {
                    foreach ($patientsDB->first()->suspectCases as $suspectCase) {
                        if ($suspectCase->sample_at->format('d-m-Y') == $patient['fecha_toma_muestra']) {
                            $warningMsg .= 'La muestra ' . $patient['id_muestra'] . ' no fué ingresada. El paciente ' . $patient['id_paciente'] . ' ya tiene muestra para el día ' . $patient['fecha_toma_muestra'] . "<br>";
                            DB::rollBack();
                            continue 2;
                        }
                    }
                }

                $patient_create = Patient::where('run', $run)
                    ->orWhere('other_identification', $run)
                    ->get()
                    ->first();

                if ($patient_create) {
                    if (!$patient_create->demographic) {
                        $new_demographic = new Demographic();

                        $commune = Commune::where('name', 'like', '%' . trim($patient['comuna_paciente']) . '%')->first();

                        $new_demographic->address = $patient['dirección_paciente'];
                        $new_demographic->commune_id = $commune->id;
                        $new_demographic->region_id = $commune->region_id;
                        $new_demographic->nationality = $patient['pais_origen_paciente'];
                        $new_demographic->telephone = $patient['telefono_paciente'];
                        $new_demographic->email = $patient['paciente_email'];
                        $new_demographic->patient_id = $patient_create->id;

                        $new_demographic->save();
                    }
                }

                if ($patient_create) {
                    $new_suspect_case = new SuspectCase();
//                    if ($patient['tiposolicitud'] == 'api')
//                        $new_suspect_case->id = $patient['codigo_muestra_cliente'];

                    $new_suspect_case->laboratory_id = Auth::user()->laboratory_id;
                    $new_suspect_case->sample_type = $patient['tipo_muestra'];
                    $new_suspect_case->sample_at = Carbon::parse($patient['fecha_toma_muestra'] . ' ' . $patient['hora_muestra']);
                    //                $new_suspect_case->sample_at          = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['fecha_toma_muestra']))->format('Y-m-d H:i:s');

                    $user = User::where('run', explode('-', $patient['rut_profesional'])[0])
                        ->get()
                        ->first();

                    if (!$user) {
                        $user = Auth::user();
                    }

                    $new_suspect_case->user_id = $user->id;

                    if ($patient['fecha_recepcion_muestra'] != null) {
                        $new_suspect_case->reception_at = Carbon::parse($patient['fecha_recepcion_muestra'] . ' ' . $patient['hora_recepcion']);
                        //                    $new_suspect_case->reception_at       = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['fecha_recepcion_muestra']))->format('Y-m-d H:i:s');
                        $new_suspect_case->receptor_id = $user->id;
                    }

                    if ($patient['fecha_resultado_muestra'] != null) {
                        $new_suspect_case->pcr_sars_cov_2_at = Carbon::parse($patient['fecha_resultado_muestra'] . ' ' . $patient['hora_resultado']);
                        //                    $new_suspect_case->pcr_sars_cov_2_at       = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['fecha_resultado_muestra']))->format('Y-m-d H:i:s');
                        $new_suspect_case->validator_id = $user->id;

                    }

                    if ($patient['resultado'] != null) {
                        if ($patient['resultado'] == 'Positivo') {
                            $new_suspect_case->pcr_sars_cov_2 = 'positive';
                        }
                        if ($patient['resultado'] == 'Negativo') {
                            $new_suspect_case->pcr_sars_cov_2 = 'negative';
                        }
                        if ($patient['resultado'] == 'Indeterminado') {
                            $new_suspect_case->pcr_sars_cov_2 = 'undetermined';
                        }
                        if ($patient['resultado'] == 'Muestra no apta') {
                            $new_suspect_case->pcr_sars_cov_2 = 'rejected';
                        }
                    } else {
                        $new_suspect_case->pcr_sars_cov_2 = 'pending';
                    }

                    $establishmentCodeDeis = explode(' ', $patient['establecimiento'])[0];

                    $establishment = Establishment::where('new_code_deis', $establishmentCodeDeis)
                        ->get()
                        ->first();

                    if ($establishment) {
                        $new_suspect_case->establishment_id = $establishment['id'];
                    } else {
                        $new_suspect_case->establishment_id = 51;
                        $new_suspect_case->observation = $patient['establecimiento'];
                    }

                    $new_suspect_case->run_medic = $patient['rut_medico_solicitante'];
                    $new_suspect_case->minsal_ws_id = $patient['id_muestra'];
                    $new_suspect_case->epivigila = $patient['epivigila'];
                    $new_suspect_case->patient_id = $patient_create->id;

                    if ($patient['busqueda_activa'] == 'VERDADERO') {
                        $new_suspect_case->case_type = 'Busqueda activa';
                    } else {
                        $new_suspect_case->case_type = 'Atención médica';
                    }

                    if ($patient['sexo_paciente'] == 'M') {
                        $new_suspect_case->gender = 'male';
                    }
                    if ($patient['sexo_paciente'] == 'F') {
                        $new_suspect_case->gender = 'female';
                    }
                    if ($patient['sexo_paciente'] == 'Intersex') {
                        $new_suspect_case->gender = 'other';
                    }
                    if ($patient['sexo_paciente'] == 'Desconocido') {
                        $new_suspect_case->gender = 'unknown';
                    }
                    $new_suspect_case->age = $patient['edad_paciente'];

                    $isSaved = $new_suspect_case->save();

                    if ($isSaved && $new_suspect_case->pcr_sars_cov_2_at != null && $new_suspect_case->pcr_sars_cov_2 != null) {
                        \PDF::loadView('lab.results.result', ['case' => $new_suspect_case])
                            ->save(storage_path() . '/app/suspect_cases/' . $new_suspect_case->id . '.pdf');
                        $new_suspect_case->file = true;
                        $new_suspect_case->save();
                        $casesInsertedNumber++;
                    }
                }
                DB::commit();

            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }

        $timeElapsed = microtime(true) - $timeStart;
//        error_log($timeElapsed);

        if ($warningMsg != '') {
            $warningMsg = "Se insertaron exitosamente $casesInsertedNumber casos." . "<br>" . $warningMsg;
            session()->flash('warning', $warningMsg);
        } else
            session()->flash('success', "Se insertaron exitosamente $casesInsertedNumber casos.");

        return redirect()->route('lab.bulk_load_from_pntm.index');
    }

//    public function bulk_load_import_from_pntm_passport(Request $request){
//        set_time_limit(0);
//        $file = $request->file('file');
//
//        $patientsCollection = Excel::toCollection(new PatientImport, $file);
//
//        foreach ($patientsCollection[0] as $patient) {
//            $new_patient = new Patient();
//
//            $new_patient->run = null;
//            $new_patient->dv  = null;
//
//            $new_patient->other_identification  = $patient['id_paciente'];
//
//            $new_patient->name            = $patient['nombre_paciente'];
//            $new_patient->fathers_family  = $patient['apellido_paterno_paciente'];
//            $new_patient->mothers_family  = $patient['apellido_materno_paciente'];
//
//            if($patient['sexo_paciente'] == 'M'){
//                $new_patient->gender = 'male';
//            }
//            if($patient['sexo_paciente'] == 'F'){
//                $new_patient->gender = 'female';
//            }
//            if($patient['sexo_paciente'] == 'Intersex'){
//                $new_patient->gender = 'other';
//            }
//            if($patient['sexo_paciente'] == 'Desconocido'){
//                $new_patient->gender = 'unknown';
//            }
//
//            //                $new_patient->birthday        = Carbon::parse($patient['fecha_nacimiento_paciente']);
//            $new_patient->birthday = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($patient['fecha_nacimiento_paciente']))->format('Y-m-d H:i:s');
//            $new_patient->save();
//
//            $new_demographic = new Demographic();
//
//            $commune = Commune::where('name', 'like', '%' . trim($patient['comuna_paciente']) . '%')->first();
//
//            $new_demographic->address       = $patient['dirección_paciente'];
//            $new_demographic->commune_id    = $commune->id;
//            $new_demographic->region_id     = $commune->region_id;
//            $new_demographic->nationality   = $patient['pais_origen_paciente'];
//            $new_demographic->telephone     = $patient['telefono_paciente'];
//            $new_demographic->email         = $patient['paciente_email'];
//            $new_demographic->patient_id    = $new_patient->id;
//
//            $new_demographic->save();
//
//            $suspectCase = SuspectCase::find($patient['codigo_muestra_cliente']);
//            $suspectCase->sample_type = 'TÓRULAS NASOFARÍNGEAS';
//            $suspectCase->patient_id = $new_patient->id;
//            // dd($suspectCase);
//            $suspectCase->save();
//
//        }
//
//        session()->flash('success', 'El archivo fue cargado exitosamente.');
//        return redirect()->route('lab.bulk_load_from_pntm.index');
//    }

    public function results_import(Request $request){
        $file = $request->file('file');
        $patientsCollection = Excel::toCollection(new PatientImport, $file);
        $startDate = Carbon::now()->subWeeks(4)->setTime(0,0,0,0);
        $endDate = Carbon::now()->setTime(0,0,0,0);
        $cont = 0;

        foreach ($patientsCollection[0] as $data) {
            $id_esmeralda = NULL;
            $resultado = NULL;
            $fecha_resultado = NULL;

            if (!isset($data['id esmeralda'])) {
                session()->flash('warning', 'No se encuentra columna id esmeralda o no tiene datos. Por favor verifique que esta correctamente escrito y no tiene espacios en blanco.');
                return view('lab.suspect_cases.import_results');
            }

            if (!isset($data['resultado'])) {
                session()->flash('warning', 'No se encuentra columna resultado o no tiene datos. Por favor verifique que esta correctamente escrito y no tiene espacios en blanco.');
                return view('lab.suspect_cases.import_results');
            }

            if (!isset($data['fecha resultado'])) {
                session()->flash('warning', 'No se encuentra columna fecha resultado o no tiene datos. Por favor verifique que esta correctamente escrito y no tiene espacios en blanco.');
                return view('lab.suspect_cases.import_results');
            }

            $id_esmeralda = $data['id esmeralda'];
            $resultado = $data['resultado'];
            $fecha_resultado_carbon = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['fecha resultado']));
            $fecha_resultado = $fecha_resultado_carbon->format('Y-m-d H:i:s');

            if(!$fecha_resultado_carbon->betweenIncluded($startDate, $endDate)){
                session()->flash('warning', "La fecha de resultado {$fecha_resultado_carbon->format('d-m-Y')} de la muestra $id_esmeralda debe estar entre {$startDate->format('d-m-Y')} y {$endDate->format('d-m-Y')}.");
                return view('lab.suspect_cases.import_results');
            }

            if ($resultado == "negativo") {
                $resultado = "negative";
            }
            if ($resultado == "pendiente") {
                $resultado = "pending";
            }
            if ($resultado == "positivo") {
                $resultado = "positive";
            }
            if ($resultado == "rechazado") {
                $resultado = "rejected";
            }
            if ($resultado == "indeterminado") {
                $resultado = "undetermined";
            }

            if ($id_esmeralda != NULL && $resultado != NULL && $fecha_resultado != NULL) {
                $suspectCase = SuspectCase::find($id_esmeralda);
                if ($suspectCase) {
                    $suspectCase->pcr_sars_cov_2 = $resultado;
                    $suspectCase->pcr_sars_cov_2_at = $fecha_resultado;
                    $suspectCase->validator_id = Auth::id();
                    $suspectCase->save();
                    $cont += 1;

                    if ($request->generate_pdf == true) {
                        \PDF::loadView('lab.results.result', ['case' => $suspectCase])
                            ->save(storage_path() . '/app/suspect_cases/' . $suspectCase->id . '.pdf');
                        $suspectCase->file = true;
                        $suspectCase->save();
                    }

                    if ($request->upload_to_pntm == true && $suspectCase->pcr_result_added_at == null) {
                        $response = WSMinsal::resultado_muestra($suspectCase);
                        if ($response['status'] == 0) {
                            $suspectCase->ws_pntm_mass_sending = false;
                            $suspectCase->pcr_result_added_at = null;
                            $suspectCase->save();
                            session()->flash('info', 'Error al subir resultado de muestra ' . $suspectCase->id . ' en MINSAL. ' . $response['msg']);
                            return view('lab.suspect_cases.import_results');
                        }
                        if ($response['status'] == 1) {
                            $suspectCase->ws_pntm_mass_sending = true;
                            $suspectCase->pcr_result_added_at = Carbon::now();
                            $suspectCase->save();
                        }
                    }

                }
            }
        }

        session()->flash('success', 'Se han modificado ' . $cont . ' casos.');
        return view('lab.suspect_cases.import_results');
    }



    public function ws_test(){
        $case = SuspectCase::find(507934);
        $estadoMuestra = WSMinsal::obtiene_estado_muestra($case);
        dd($estadoMuestra);
        return redirect()->back();
    }


    public function positiveCondition(Request $request, SuspectCase $suspectCase){

        $suspectCase->positive_condition = $request->positive_condition;
        $suspectCase->save();
        session()->flash('success', 'Se añadio el tipo de infección correctamente');
        return redirect()->back();

    }



}
