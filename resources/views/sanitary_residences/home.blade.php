@extends('layouts.app')

@section('title', 'Home Residencias')

@section('content')

@include('sanitary_residences.nav')



<h3 class="mb-3" style="text-align:center">Módulo Residencias</h3>
<h4 class="mb-3" style="text-align:center"> Se les recuerda lo Siguiente</h4>
<ul style="list-style-type:disc; font-size:15px">
  <li>No se debe compartir el usuario y contraseña con otros compañeros.</li>
  <li>Se deberá de digitar en el sistema con un máximo de 5 horas desde que se tomó el signo vital</li>
  <li>La asignación de la (o las) residencia sanitaria que deberá de cumplir sus labores lo realiza su jefatura, en caso que no le salga ninguna al momento de ingresar al módulo se tendrá que comunicar con ellos.</li>
  <li>Si comete un error de digitación (ej: dos veces digitados un paciente en el mismo cuarto) y necesita borrar un booking. Deberá de comunicarse con su jefatura ya que ellos tienen los privilegios para realizar esta acción.</li>
  <li>En caso de Traslado de paciente a otra Residencia Sanitaria, se deberá comunicar con su jefatura.</li>
  <li>Tiene 24 horas para poder modificar un signo vital-evolución-indicación, posterior a este tiempo no se puede modificar lo digitado.</li>
  <li>Está prohibido publicar-difundir pantallazos de cualquier información sensible en RRSS.</li>
</ul>


@endsection

@section('custom_js')

@endsection