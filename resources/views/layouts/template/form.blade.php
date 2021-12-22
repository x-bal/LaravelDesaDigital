@php
$forms = \DB::getSchemaBuilder()->getColumnListing($table);
@endphp
<strong>Form {{ substr(str_replace('_',' ',$table),0,-1) }}</strong>
<div class="form-group row">
    @foreach($forms as $form)
        @if(!str_contains($form,'_pem'))
            @if(!in_array($form,['created_at','updated_at','id']))
                @if(str_contains($form,'jenis_kelamin'))
                <div class="form-group col-md-4 my-2">
                    <label for="{{ $form }}" class="label-form">{{ str_replace('_',' ',$form) }}</label>
                    <select name="{{ $form }}" id="{{ $form }}" class="form-control @error($form) is-invalid @enderror">
                        @foreach(\App\Models\PermohonanSuratKuasa::getJeniskelamin() as $data)
                            <option value="{{ $data }}">{{ $data }}</option>
                        @endforeach
                    </select>
                    @error($form)
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                @elseif(in_array($form,['desa_pen','kecamatan_pen','kabupaten_pen']))
                <div class="form-group col-md-4 my-2">
                <label for="{{ $form }}" class="label-form">{{ str_replace('_',' ',$form) }}</label>
                    <select name="{{ $form }}" id="{{ $form }}" class="form-control @error($form) is-invalid @enderror">
                        @if($form == 'desa_pen')
                            @foreach(\App\Models\Desa::get() as $desa)
                            <option value="{{ $desa->nama_desa }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        @endif
                        @if($form == 'kecamatan_pen')
                            @foreach(\App\Models\Kecamatan::get() as $kecamatan)
                            <option value="{{ $kecamatan->nama_kecamatan }}">{{ $kecamatan->nama_kecamatan }}</option>
                            @endforeach
                        @endif
                        @if($form == 'kabupaten_pen')
                            @foreach(\App\Models\Kabupaten::get() as $kabupaten)
                            <option value="{{ $kabupaten->nama_kabupaten }}">{{ $kabupaten->nama_kabupaten }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error($form)
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                @elseif(in_array($form,['orangtua_ayah_id','orangtua_ibu_id']))
                <div class="form-group col-md-4 my-2">
                    <label for="{{ $form }}" class="form-label">{{$form}}</label>
                    <select id="{{ $form }}" name="{{ $form }}" class="form-control select2-ajax-cetak-surat @error('{{ $form }}') is-invalid @enderror"></select>
                    @error($form)
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                @else
                <div class="form-group col-md-4 my-2">
                    <label for="" class="label-form">{{ str_replace('_',' ',$form) }}</label>
                    <input type="{{ in_array($form,['berlaku_mulai','berlaku_sampai','tanggal_lahir_pen','tanggal_lahir_pem']) ? 'date' : 'text' }}" required {{ $form == 'permohonan_surat_id' ? 'readonly' : '' }} name="{{ $form }}"  value="{{ $form == 'permohonan_surat_id' ? $jenis_surat->id : '' }}" class="form-control @error($form) is-invalid @enderror" placeholder="{{ str_replace('_',' ',$form) }}">
                    @error($form)
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                @endif
            @endif
        @endif
    @endforeach
</div>