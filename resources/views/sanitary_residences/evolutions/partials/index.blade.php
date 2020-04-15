<h3 class="mb-3">Listado de Evoluciones</h3>

<table class="table table-sm table-bordered table-responsive">
    <thead>
        <tr>
            <th>Fecha y hora</th>            
            <th>Evolución</th>
            <th>Funcionario</th>            
        </tr>
    </thead>
    <tbody>
        @foreach($booking->evolutions->reverse() as $evolutions)
        <tr>
        <td nowrap>{{ $evolutions->created_at->format('d-m-Y H:i') }}</td>
        <td>{{ $evolutions->content }}</td>        
        <td nowrap>{{ $evolutions->user->name }}</td>            
        </tr>
        @endforeach
    </tbody>
</table>
