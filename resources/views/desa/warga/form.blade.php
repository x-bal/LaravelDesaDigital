<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" name="nik" id="nik" value="{{ $masyarakat->nik ?? old('nik') }}" class="form-control @error('nik') is-invalid @enderror">
            @error('nik')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" value="{{ $masyarakat->nama ?? old('nama') }}" class="form-control @error('nama') is-invalid @enderror">
            @error('nama')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" value="{{ $masyarakat->jenis_kelamin ?? old('jenis_kelamin') }}" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                @foreach(App\Models\Warga::getPossibleJenisKelamin() as $data)
                <option @if($masyarakat->jenis_kelamin ?? old('jenis_kelamin') == $data) selected @endif value="{{ $data }}">{{ $data }}</option>
                @endforeach
            </select>
            @error('jenis_kelamin')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ $masyarakat->tempat_lahir ?? old('tempat_lahir') }}" class="form-control @error('tempat_lahir') is-invalid @enderror">
            @error('tempat_lahir')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $masyarakat->tanggal_lahir ?? old('tanggal_lahir') }}" class="form-control @error('tanggal_lahir') is-invalid @enderror">
            @error('tanggal_lahir')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="desa" class="form-label">Desa</label>
            <input type="text" name="desa" id="desa" value="{{ $masyarakat->desa ?? old('desa') }}" class="form-control @error('desa') is-invalid @enderror">
            @error('desa')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <input type="text" name="kecamatan" id="kecamatan" value="{{ $masyarakat->kecamatan ?? old('kecamatan') }}" class="form-control @error('kecamatan') is-invalid @enderror">
            @error('kecamatan')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="kabupaten" class="form-label">Kabupaten</label>
            <input type="text" name="kabupaten" id="kabupaten" value="{{ $masyarakat->kabupaten ?? old('kabupaten') }}" class="form-control @error('kabupaten') is-invalid @enderror">
            @error('kabupaten')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="provinsi" class="form-label">Provinsi</label>
            <input type="text" name="provinsi" id="provinsi" value="{{ $masyarakat->provinsi ?? old('provinsi') }}" class="form-control @error('provinsi') is-invalid @enderror">
            @error('provinsi')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>