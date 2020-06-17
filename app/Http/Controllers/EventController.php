<?php

namespace App\Http\Controllers;

use App\Tracing\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $event = new Event($request->All());
        $event->user_id = auth()->id();
        if($request->input('sympthoms_array')) {
            $event->symptoms = implode (", ",$request->input('sympthoms_array'));
        }
        $event->save();

        if($request->input('next_action')) {
            switch ($request->input('next_action')) {
                case 0:
                    $event->tracing->status = 0;
                    break;
                case 1:
                    $event->tracing->next_control_at = $event->event_at->add(1,'day');
                    break;
                case 2:
                    $event->tracing->next_control_at = $event->event_at->add(2,'day');
                    break;
                case 3:
                    $event->tracing->next_control_at = $event->event_at->add(3,'day');
                    break;
            }
            $event->tracing->save();
        }

        session()->flash('info', 'Evento almacenado');

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tracing\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tracing\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
