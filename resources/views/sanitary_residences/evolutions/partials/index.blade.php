<h3 class="mb-3">Listado de Evoluciones</h3>

@foreach($booking->evolutions->reverse() as $evolutions)
<div class="row mb-4">
    <div class="col-10 col-md-11 text-justify">
        {{ $evolutions->content }}
        <footer class="blockquote-footer">{{ $evolutions->created_at->format('d-m-Y H:i') }}
            <cite>{{ $evolutions->user->name }}</cite>
        </footer>
    </div>
    <div class="col-2 col-md-1">
        @if($evolutions->created_at->diff(now())->days < 1)
            <button type="submit" class="btn btn-outline-secondary btn-sm" id="btn_evoluciones_{{$evolutions->id}}">
                <i class="fas fa-edit"></i>
        @endif
    </div>
</div>

@endforeach