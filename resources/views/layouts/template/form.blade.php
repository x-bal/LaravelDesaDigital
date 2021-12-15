@php
$forms = \DB::getSchemaBuilder()->getColumnListing($table);
@endphp

<div class="row">
    @foreach($forms as $form)
        @if(!in_array($form,['created_at','updated_at','id']))
        <div class="col-md-4 my-2">
            <input type="{{ in_array($form,['berlaku_mulai','berlaku_sampai']) ? 'date' : 'text' }}" {{ $form == 'permohonan_surat_id' ? 'readonly' : '' }} name="{{ $form }}"  value="{{ $form == 'permohonan_surat_id' ? $jenis_surat->id : '' }}" class="form-control" placeholder="{{ $form }}">
        </div>
        @endif
    @endforeach
</div>