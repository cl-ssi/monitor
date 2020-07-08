<div class="form-row">

    <fieldset class="form-group col-6 col-md-3 alert-warning">
        <label for="for_result_ifd_at">Fecha Resultado IFD</label>
        <input type="datetime-local" class="form-control" id="for_result_ifd_at" disabled
            value="{{( isset($suspectCase->result_ifd_at))?  $suspectCase->result_ifd_at->format('Y-m-d\TH:i:s'):'' }}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-2 alert-warning">
        <label for="for_result_ifd">Resultado IFD</label>
        <select disabled id="for_result_ifd" class="form-control">
            <option>{{ $suspectCase->result_ifd }}</option>
        </select>
    </fieldset>


    <fieldset class="form-group col-6 col-md-2 alert-warning">
        <label for="for_subtype">Subtipo</label>
        <select disabled id="for_subtype" class="form-control">
            <option value="">{{ $suspectCase->subtype }}</option>
        </select>
    </fieldset>

</div>

<div class="form-row">

    <fieldset class="form-group col-6 col-md-3 alert-danger">
        <label for="for_pscr_sars_cov_2_at">Fecha Resultado PCR</label>
        <input type="datetime-local" class="form-control" id="for_pscr_sars_cov_2_at"
            value="{{ isset($suspectCase->pscr_sars_cov_2_at)? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d\TH:i:s'):'' }}"
            disabled>
    </fieldset>

    <fieldset class="form-group col-6 col-md-2 alert-danger">
        <label for="for_pscr_sars_cov_2">PCR SARS-Cov2</label>
        <select id="for_pscr_sars_cov_2" class="form-control" disabled>
            <option>{{ $suspectCase->covid19 }}</option>
        </select>
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_sent_isp_at">Fecha envío lab externo</label>
        <input type="date" class="form-control" id="for_sent_isp_at" disabled
            value="{{ isset($suspectCase->sent_isp_at)? $suspectCase->sent_isp_at->format('Y-m-d'):'' }}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_external_laboratory">Laboratorio externo</label>
        <select disabled id="for_external_laboratory" class="form-control">
            <option>{{ $suspectCase->external_laboratory }}</option>
        </select>
    </fieldset>


    <fieldset class="form-group col-12 col-md-3">
        <label for="for_file">Archivo</label>
        <div class="custom-file">
            <input type="file" disabled class="custom-file-input" id="forfile" lang="es" multiple>
            <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
        </div>
        @if($suspectCase->files != null)
            @foreach($suspectCase->files as $file)
                <a href="{{ route('lab.suspect_cases.download', $file->id) }}"
                    target="_blank" data-toggle="tooltip" data-placement="top"
                    data-original-title="{{ $file->name }}">Resultado <i class="fas fa-paperclip"></i>&nbsp
                </a>
                @can('SuspectCase: file delete')
                 - <a href="{{ route('lab.suspect_cases.fileDelete', $file->id) }}" onclick="return confirm('Estás seguro Cesar?')">
                    [ Borrar ]
                </a>
                @endcan
            @endforeach
        @endif
    </fieldset>
</div>
