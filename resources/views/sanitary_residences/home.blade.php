@extends('layouts.app')

@section('title', 'Home Residencias')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3" style="text-align:center">Módulo Residencias</h3>
<h4 class="mb-3" style="text-align:center"> Se les recuerda lo Siguiente</h4>
<ul style="list-style-type:disc; font-size:20px">
  <li>No se debe compartir el usuario y contraseña con otros compañeros</li>
  <li>La asignación de la (o las) residencia sanitaria que debera de cumplir sus labores lo realiza su jefatura, en caso que no les salg ninguna al momento de ingresar al módulo deberá comunicarse con ellos</li>
  <li>En caso de error de digitación (ej: dos veces digitados un paciente en el mismo cuarto). Su jefatura tiene los privilegios para borrar un Booking</li>
  <li>En caso de Traslado de paciente a otra Residencia Sanitaria, deberá comunicarse y realizarlo su jefatura</li>
  <li>Tiene 24 horas para poder modificar un signo vital-evolución-indicación, posterior a este tiempo no se puede modificar lo digitado</li>
</ul>


@endsection

@section('custom_js')

@endsection