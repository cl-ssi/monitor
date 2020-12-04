@extends('layouts.app')
@section('title', 'Crear Paciente')
@section('content')


@foreach($suspectCases as $key => $suspectCase)
    @include('lab.suspect_cases.partials.notification_form_small_content', ['suspectCase' => $suspectCase, 'isBulkPrint' => true])
@endforeach



@endsection

<style>
    @media print{
        @page {
            size: auto;   /* auto is the initial value */
            size: letter portrait;
            /*margin: 0;  !* this affects the margin in the printer settings *!*/
        }
    }
</style>
