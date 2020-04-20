<h3 class="mb-3">Listado de Indicaciones</h3>

@foreach($booking->indicaciones->reverse() as $indicaciones)
<div class="row mb-4">
    <div class="col-10 col-md-11">
        <span class="text-justify" style="white-space: pre-wrap;">{{ $indicaciones->content }}</span>
        <footer class="blockquote-footer">{{ $indicaciones->created_at->format('d-m-Y H:i') }}
            <cite>{{ $indicaciones->user->name }}</cite>
        </footer>
    </div>
    <div class="col-2 col-md-1">
        @if($indicaciones->created_at->diff(now())->days < 1)
            <button type="submit" class="btn btn-outline-secondary btn-sm" id="btn_indications_{{$indicaciones->id}}">
                <i class="fas fa-edit"></i>
        @endif
    </div>
</div>

@endforeach