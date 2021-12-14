<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="no_antrian" class="form-label">No Antrian</label>
            <input type="text" name="no_antrian" id="no_antrian" value="{{ $antrian->no_antrian ?? $no_antri + 1  }}" class="form-control @error('no_antrian') is-invalid @enderror" readonly>
            @error('no_antrian')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="warga" class="form-label">Warga</label>
            <select name="warga" id="warga" class="form-control @error('warga') is-invalid @enderror">
                <option disabled selected>-- Pilih Warga --</option>
                @foreach($warga as $wrg)
                <option {{ $antrian->id ? $antrian->warga_id == $wrg->id ? 'selected' : '' : '' }} value="{{ $wrg->id }}">({{ $wrg->nik }}) {{ $wrg->nama_warga }}</option>
                @endforeach
            </select>
            @error('warga')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="jenis" class="form-label">Jenis Surat</label>
            <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror">
                <option disabled selected>-- Pilih Jenis Surat --</option>
                @foreach($jenis as $jns)
                <option {{ $antrian->id ? $antrian->jenis_surat_id == $jns->id ? 'selected' : '' : '' }} value="{{ $jns->id }}">{{ $jns->jenis_surat }}</option>
                @endforeach
            </select>
            @error('jenis')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="loket" class="form-label">Loket</label>
            <select name="loket" id="loket" class="form-control @error('loket') is-invalid @enderror">
                <option disabled selected>-- Pilih Loket --</option>
                @foreach($loket as $lkt)
                <option {{ $antrian->id ? $antrian->loket_id == $lkt->id ? 'selected' : '' : '' }} value="{{ $lkt->id }}">{{ $lkt->nama }}</option>
                @endforeach
            </select>
            @error('loket')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>