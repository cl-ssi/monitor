@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3">Listado de archivos</h3>

<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<div class="table-responsive">
<table class="table table-sm" id="tabla_files">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombre</th>
            <th>Fecha toma de muestra</th>
            <th>Fecha de resultado</th>
            <th>Archivo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($files as $file)
        <tr>
           <td>
             @if($file->suspectCase->patient != null)
               {{$file->suspectCase->patient->run}}
             @endif
           </td>
           <td>
             @if($file->suspectCase->patient != null)
               {{ $file->suspectCase->patient->name }} {{ $file->suspectCase->patient->fathers_family }} {{ $file->suspectCase->patient->mothers_family }}
            @endif
           </td>
            <td>{{ $file->suspectCase->sample_at }}</td>
            <td>{{ $file->suspectCase->result_ifd_at }}</td>
            <td><a href="{{ route('lab.suspect_cases.download', $file->id) }}"
                    target="_blank" data-toggle="tooltip" data-placement="top"
                    data-original-title="{{ $file->name }}">
                    {{-- {{$file->name}} --}}
                    <i class="fas fa-paperclip"></i>&nbsp
                </a></td>
        </tr>
        @endforeach
        @foreach ($suspectCases as $key => $suspectCase)
          <tr>
            <td>
              @if($suspectCase->patient != null)
                {{$suspectCase->patient->run}}
              @endif
            </td>
            <td>
              @if($suspectCase->patient != null)
                {{ $suspectCase->patient->name }} {{ $suspectCase->patient->fathers_family }} {{ $suspectCase->patient->mothers_family }}
             @endif
            </td>
             <td>{{ $suspectCase->sample_at }}</td>
             <td>{{ $suspectCase->result_ifd_at }}</td>
            <td><a href="{{ route('lab.print', $suspectCase) }}"
                    target="_blank" data-toggle="tooltip" data-placement="top"> <i class="fas fa-paperclip"></i>&nbsp
                </a>
            </td>
          </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection

@section('custom_js')
  <script type="text/javascript">
  function exportF(elem) {
      var table = document.getElementById("tabla_files");
      var html = table.outerHTML;
      var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
      var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
      elem.setAttribute("href", url);
      elem.setAttribute("download", "files.xls"); // Choose the file name
      return false;
  }
  </script>
@endsection
