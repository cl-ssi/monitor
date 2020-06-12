@extends('layouts.app')

@section('title', 'Listado de encuestas')

@section('content')
<h3 class="mb-3">Listado de encuestas</h3>



@foreach($encuestas as $enc)

    <li>{{ $enc->respuesta }}</li>

@endforeach


<ul>
    @forelse ($enc->audits as $audit)
    <li>
        @lang('article.updated.metadata', $audit->getMetadata())

        @foreach ($audit->getModified() as $attribute => $modified)
        <ul>
            <li>@lang('article.'.$audit->event.'.modified.'.$attribute, $modified)</li>
        </ul>
        @endforeach
    </li>
    @empty
    <p>@lang('article.unavailable_audits')</p>
    @endforelse
</ul>



@endsection

@section('custom_js')

@endsection
