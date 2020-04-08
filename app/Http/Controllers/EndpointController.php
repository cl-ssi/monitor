<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pharmacies\Dispatch;

class EndpointController extends Controller
{
  public function receiveDispatchC19(Request $request)
  {
    //dd("holaaaa", $request->dispatch);
    $dispatch = new Dispatch();
    $dispatch->fill($request->dispatch);
    $dispatch->save();

    //return view('welcome');
  }
}
