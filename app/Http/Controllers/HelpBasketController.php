<?php

namespace App\Http\Controllers;

use App\Basket\HelpBasket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Commune;

class HelpBasketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $helpbaskets = HelpBasket::orderByDesc('updated_at')->get();;
        return view('help_basket.index', compact('helpbaskets'));
    }


    function compress_image($src, $dest, $quality)
    {
        $info = getimagesize($src);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($src);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($src);
        } else {
            die('Unknown image file format');
        }

        //compress and save file to jpg
        imagejpeg($image, $dest, $quality);

        //return destination file
        return $dest;
    }

    public function georeferencing()
    {
        //

        $data = array();

        $helpbaskets = HelpBasket::all();

        foreach ($helpbaskets as $key => $helpbasket) {
            if ($helpbasket->latitude != null and $helpbasket->longitude != null) {
                $data[$helpbasket->address . " " . $helpbasket->number . ", " . $helpbasket->commune][$helpbasket->identifier]['paciente'] = $helpbasket;
            }
        }



        return view('help_basket.georeferencing', compact('helpbaskets', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $communes = Commune::where('region_id', 1)->orderBy('name')->get();

        //$communes = Commune::where('region_id',[env('REGION')])->orderBy('name')->get();
        //$communes = Commune::where('region_id',[config('app.REGION')])->orderBy('name')->get();
        return view('help_basket.create', compact('communes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $integrityrun = HelpBasket::whereNotNull('run')->where('run', $request->run)->exists();
        $integrityaddress = HelpBasket::where('address', $request->address)->where('number', $request->number)->where('department', $request->department)->exists();
        //dd($integrityrun);
        if ($integrityrun != null) {
            session()->flash('danger', 'A este RUN ya se le entrego canasta familiar. Vuelva a ingresar datos');
            return redirect()->route('help_basket.create');
        } else {
            if ($integrityaddress) {
                //dd($integrityaddress->address);
                session()->flash('danger', 'Ya fue entregado anteriormente a esta dirección');
                return redirect()->route('help_basket.create');
            } else {
                $helpbaket = new HelpBasket($request->All());
                $helpbaket->user_id = auth()->user()->id;


                if ($request->file('photo')) {
                    $storage = $helpbaket->run;
                    $storage += $helpbaket->other_identification;
                    //$storage = $storage.'_id';
                    $ext = $request->file('photo')->extension();
                    $helpbaket->photo = $request->file('photo')->storeAs('help_baskets', $storage . '.' . $ext);
                    //$this->sendRequest($uri);compress_image('boy.jpg', 'destination.jpg', 50);

                }

                if ($request->file('photoid')) {
                    $storage = $helpbaket->run;
                    $storage += $helpbaket->other_identification;
                    $storage = $storage . '_id';
                    $ext = $request->file('photoid')->extension();
                    $helpbaket->photoid = $request->file('photoid')->storeAs('help_baskets', $storage . '.' . $ext);
                }


                $helpbaket->save();
                session()->flash('success', 'Se recepcionó la canasta exitosamente');
                return redirect()->route('help_basket.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function show(HelpBasket $helpBasket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function edit(HelpBasket $helpBasket)
    {
        //
        //$communes = Commune::where('region_id', 1)->orderBy('name')->get();
        $communes = Commune::where('region_id', [env('REGION')])->orderBy('name')->get();
        return view('help_basket.edit', compact('helpBasket', 'communes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HelpBasket $helpBasket)
    {
        //
        //dd($request->file('photo'));
        $helpBasket->fill($request->all());
        $helpBasket->user_id = auth()->user()->id;

        if ($request->file('photo')) {
            $storage = $helpBasket->run;
            $storage += $helpBasket->other_identification;
            $ext = $request->file('photo')->extension();
            $helpBasket->photo = $request->file('photo')->storeAs('help_baskets', $storage . '.' . $ext);
        }

        if ($request->file('photoid')) {
            $storage = $helpBasket->run;
            $storage += $helpBasket->other_identification;
            $storage = $storage . '_id';
            $ext = $request->file('photoid')->extension();
            $helpBasket->photoid = $request->file('photoid')->storeAs('help_baskets', $storage . '.' . $ext);
        }

        $helpBasket->save();
        session()->flash('success', 'Se actualizo los datos exitosamente');
        return redirect()->route('help_basket.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Basket\HelpBasket  $helpBasket
     * @return \Illuminate\Http\Response
     */
    public function destroy(HelpBasket $helpBasket)
    {
        //
        $helpBasket->delete();
        session()->flash('success', 'Entrega de Canaste Eliminada Exitosamente');
        return redirect()->route('help_basket.index');
    }




    public function download($storage, $file)
    {
        return Storage::download($storage . '/' . $file, 'resultado.pdf');
    }

    public function excel()
    {
        $helpbaskets = HelpBasket::orderByDesc('updated_at')->get();;
        return view('help_basket.excel', compact('helpbaskets'));
    }
}
