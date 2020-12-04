@extends('layouts.app')
@section('title', 'Crear Paciente')

@section('content')

@include('lab.suspect_cases.partials.notification_form_small_content', ['isBulkPrint' => false])

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
{{--<style type="text/css" media="print">--}}

{{--    body {--}}
{{--        zoom:92%; /* reducción */--}}
{{--    }--}}

{{--</style>--}}
