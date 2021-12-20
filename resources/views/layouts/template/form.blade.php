@php
$forms = \DB::getSchemaBuilder()->getColumnListing($table);
@endphp
<strong>Form {{ substr(str_replace('_',' ',$table),0,-1) }}</strong>
<div class="form-group row">
    @foreach($forms as $form)
        @if(!str_contains($form,'_pem'))
            @if(!in_array($form,['created_at','updated_at','id']))
                @if(str_contains($form,'jenis_kelamin'))
                <div class="col-md-4 my-2">
                    <label for="{{ $form }}" class="label-form">{{ str_replace('_',' ',$form) }}</label>
                    <select name="{{ $form }}" id="{{ $form }}" class="form-control">
                        @foreach(\App\Models\PermohonanSuratKuasa::getJeniskelamin() as $data)
                            <option value="{{ $data }}">{{ $data }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-md-4 my-2">
                    <label for="" class="label-form">{{ str_replace('_',' ',$form) }}</label>
                    <input type="{{ in_array($form,['berlaku_mulai','berlaku_sampai','tanggal_lahir_pen','tanggal_lahir_pem']) ? 'date' : 'text' }}" {{ $form == 'permohonan_surat_id' ? 'readonly' : '' }} name="{{ $form }}"  value="{{ $form == 'permohonan_surat_id' ? $jenis_surat->id : '' }}" class="form-control" placeholder="{{ str_replace('_',' ',$form) }}">
                </div>
                @endif
            @endif
        @endif
    @endforeach
</div>