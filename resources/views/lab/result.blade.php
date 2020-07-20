@extends('layouts.public')

@section('title', 'Resultados COVID19')

@section('content')

@if($patient)
<h3 class="mb-3">Resultado de exámenes de {{ Auth::user()->name }} {{ Auth::user()->fathers_family }}</h3>

<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Establecimiento</th>
            <th>Fecha de toma de muestra</th>
            <th>Resultado COVID19</th>
            <th>Fecha del resultado</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->suspectCases as $case)
        <tr>
            <td>{{ $case->establishment->alias }}</td>
            <td>{{ $case->sample_at->format('Y-m-d') }}</td>
            <td>{{ ($case->pcr_sars_cov_2 != 'positive') ? $case->covid19 : 'Será notificado' }}</td>
            <td>{{ ($case->pcr_sars_cov_2_at) ? $case->pcr_sars_cov_2_at : '' }}</td>
            <td>
                @if($case->pcr_sars_cov_2 != 'positive')

                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@else
<h3>{{ Auth::user()->name }} {{ Auth::user()->fathers_family }} no registra exámenes de COVID19 en el Hospital Ernesto Torres Galdámes </h3>
@endif

@endsection

@section('custom_js')

<script type="text/javascript">
eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(3(){(3 a(){8{(3 b(2){7((\'\'+(2/2)).6!==1||2%5===0){(3(){}).9(\'4\')()}c{4}b(++2)})(0)}d(e){g(a,f)}})()})();',17,17,'||i|function|debugger|20|length|if|try|constructor|||else|catch||5000|setTimeout'.split('|'),0,{}))
</script>

@endsection
