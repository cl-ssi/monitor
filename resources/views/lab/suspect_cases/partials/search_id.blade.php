<form method="POST" class="form-inline" action="{{ route('lab.suspect_cases.search_id') }}">
    @csrf
    @method('POST')

    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="ID examen" required name="id" id="for_id">
        <div class="input-group-append">
          <div class="btn-group">
            @if(Route::current()->getName()=='lab.suspect_cases.ownIndex')
          <button type="submit" value="own_search" name ="submitbutton" class="btn btn-outline-secondary">Buscar</button>
            @elseif(Route::current()->getName()=='lab.suspect_cases.index')
          <button type="submit" value="index_search" name ="submitbutton" class="btn btn-outline-secondary">Buscar </button>
            @endif
          <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
          </button>
            <div class="dropdown-menu">
              <button type="submit" value="go_to" name ="submitbutton" class="dropdown-item btn btn-outline-secondary">Ir al caso</button>
            </div>
        </div>

          <!--  <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Ir al examen</button>-->
        </div>
    </div>
</form>
