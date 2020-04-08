<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pharmacies\Dispatch;

class EndpointController extends Controller
{
  public function receiveDispatchC19(Request $request)
  {
    dd("holaaaa", $request);
    // $dispatch = new Dispatch($request->all());
    // $dispatch->save();

    return view('welcome');
  }
}
