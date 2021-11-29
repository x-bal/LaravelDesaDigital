<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="warga" class="form-label">Warga</label>
            <select name="warga" id="warga" class="form-control @error('warga') is-invalid @enderror">
                <option disabled selected>-- Pilih Warga --</option>
                @foreach($warga as $wrg)
                <option {{ $permohonanSurat->id ? $permohonanSurat->warga_id == $wrg->id ? 'selected' : '' : '' }} value="{{ $wrg->id }}">({{ $wrg->nik }}) {{ $wrg->nama_warga }}</option>
                @endforeach
            </select>
            @error('warga')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="jenis" class="form-label">Jenis Surat</label>
            <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror">
                <option disabled selected>-- Pilih Jenis Surat --</option>
                @foreach($jenis as $jns)
                <option {{ $permohonanSurat->id ? $permohonanSurat->jenis_surat_id == $jns->id ? 'selected' : '' : '' }} value="{{ $jns->id }}">{{ $jns->jenis_surat }}</option>
                @endforeach
            </select>
            @error('jenis')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>