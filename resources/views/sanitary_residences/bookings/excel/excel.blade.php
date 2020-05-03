<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <table class="table table-striped table-responsive" border="2">
        <thead>
            <th>N°</th>
            <th>SS Dependencia</th>
            <th>Servicio de Salud de Origen</th>
            <th>Residencia de aislamiento temporal (hotel)</th>
            <th>Nº de piso</th>
            <th>Nº de habitación</th>
            <th>Comuna (de origen)</th>
            <th>Centro de Salud (CESFAM u Hosp. de origen)</th>
            <th>Nombre y Apellido del médico tratante</th>
            <th>Rut / DNI / Pasaporte/ Otro</th>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Sexo</th>
            <th>Fecha de Nacimiento (dd/mm/aaaa)</th>
            <th>Situación COVID-19 (Sospechoso o Confirmado)</th>
            <th>Otros antecedentes médicos (Crónicos, gestantes, otros)</th>
            <th>Prescripción médica vigente</th>
            <th>Previsión de salud</th>
            <th>Teléfono de contacto</th>
            <th>Parentesco o relación con el contacto informado</th>
            <th>FECHA DE INGRESO</th>
            <th>NACIONALIDAD</th>
            <th>CAUSAL DE ALTA</th>
            <th>FECHA DE EGRESO</th>
            <th>VACUNADO INFLUENZA</th>
            <th>EXAMEN COVID AL EGRESO</th>
            <th>OBSERVACIONES</th>
        </thead>
        <tbody>
            <tr>
                <td>{{$booking->id}}</td>
                <td>Iquique</td>
                <td>Iquique</td>
                <td>{{$booking->room->residence->name}}</td>
                <td>{{$booking->room->floor}}</td>
                <td>{{$booking->room->number}}</td>
                <td>{{ ($booking->patient->demographic)?$booking->patient->demographic->commune:'' }}</td>
                <td>{{ $booking->patient->suspectCases->last()->origin }}</td>
                <td> {{$booking->doctor}} </td>
                <td>{{$booking->patient->identifier}}</td>
                <td>{{$booking->patient->fathers_family}} {{$booking->patient->mothers_family}}</td>
                <td>{{$booking->patient->name}}</td>
                <td>{{$booking->patient->sexEsp}}</td>
                <td>{{ ($booking->patient->birthday)? $booking->patient->birthday->format('d/m/Y') :''}}</td>
                <td>{{ $booking->patient->suspectCases->first()->covid19 }}</td>
                <td>{{ $booking->morbid_history }}</td>
                <td>{{$booking->commonly_used_drugs}}</td>
                <td>{{ $booking->prevision }}</td>
                <td>{{$booking->responsible_family_member}}</td>
                <td>{{ $booking->relationship }}</td>
                <td> {{ $booking->from }} </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td> {{ $booking->observations }}</td>
            </tr>
        </tbody>

    </table>

</body>
</html>
