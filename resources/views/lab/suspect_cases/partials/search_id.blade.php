<div class="col-12 col-sm-5">
    <form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.search_id') }}">
        @csrf
        @method('POST')

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="ID examen" name="id" id="for_id">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
            </div>
        </div>

    </form>
</div>
