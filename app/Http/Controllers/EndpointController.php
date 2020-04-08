<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pharmacies\Dispatch;
use App\Pharmacies\DispatchItem;

class EndpointController extends Controller
{
<<<<<<< HEAD
  public function receiveDispatchC19(Request $request)
  {
    //obtiene datos
    $dispatch_array = json_decode($request->dispatch);
    $dispatchItems_array = json_decode($request->dispatchItems);

    //borra posibles datos
    $dispatch_id = $dispatch_array->id;
    $DispatchItem = DispatchItem::where('dispatch_id',$dispatch_id)->delete();
    $Dispatch = Dispatch::where('id',$dispatch_id)->delete();

    //ingresa cabecera
    $dispatch = new Dispatch();
    $dispatch->id = $dispatch_array->id;
    $dispatch->date = $dispatch_array->date;
    $dispatch->pharmacy_id = $dispatch_array->pharmacy_id;
    $dispatch->pharmacy = $dispatch_array->pharmacy;
    $dispatch->establishment_id = $dispatch_array->establishment_id;
    $dispatch->establishment = $dispatch_array->establishment;
    $dispatch->notes = $dispatch_array->notes;
    $dispatch->user_id = $dispatch_array->user_id;
    $dispatch->user = $dispatch_array->user;
    $dispatch->save();

    //ingresa detalles
    foreach ($dispatchItems_array as $key => $dispatchItem_array) {
      $dispatchItem = new DispatchItem();
      $dispatchItem->id = $dispatchItem_array->id;
      $dispatchItem->barcode = $dispatchItem_array->barcode;
      $dispatchItem->dispatch_id = $dispatchItem_array->dispatch_id;
      $dispatchItem->product_id = $dispatchItem_array->product_id;
      $dispatchItem->product = $dispatchItem_array->product;
      $dispatchItem->amount = $dispatchItem_array->amount;
      $dispatchItem->unity = $dispatchItem_array->unity;
      $dispatchItem->due_date = $dispatchItem_array->due_date;
      $dispatchItem->batch = $dispatchItem_array->batch;
      $dispatchItem->save();
    }
  }

  public function deleteDispatchC19(Request $request)
  {
    //borra posibles datos
    $DispatchItem = DispatchItem::where('dispatch_id',$request->dispatch_id)->delete();
    $Dispatch = Dispatch::where('id',$request->dispatch_id)->delete();
  }
=======
    public function receiveDispatchC19(Request $request)
    {
        //dd("holaaaa", $request->dispatch);
        dd($request->dispatch->toArray());
        // $dispatch = new Dispatch();
        // $dispatch->fill($request->dispatch);
        // $dispatch->save();

        //return view('welcome');
    }
>>>>>>> d2049930d84ade8e24803bedcf190cae9f8687ad
}
