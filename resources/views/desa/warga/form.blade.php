<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" name="nik" id="nik" value="{{ $warga->nik ?? old('nik') }}" class="form-control @error('nik') is-invalid @enderror">
            @error('nik')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="kk" class="form-label">KK</label>
            <input type="text" name="kk" id="kk" value="{{ $warga->kk ?? old('kk') }}" class="form-control @error('kk') is-invalid @enderror">
            @error('kk')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nama_warga" class="form-label">Nama</label>
            <input type="text" name="nama_warga" id="nama_warga" value="{{ $warga->nama_warga ?? old('nama_warga') }}" class="form-control @error('nama_warga') is-invalid @enderror">
            @error('nama_warga')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" value="{{ $warga->jenis_kelamin ?? old('jenis_kelamin') }}" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                @foreach(App\Models\Warga::getPossibleJenisKelamin() as $data)
                <option @if($warga->jenis_kelamin ?? old('jenis_kelamin') == $data) selected @endif value="{{ $data }}">{{ $data }}</option>
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
            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ $warga->tempat_lahir ?? old('tempat_lahir') }}" class="form-control @error('tempat_lahir') is-invalid @enderror">
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
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $warga->tanggal_lahir ?? old('tanggal_lahir') }}" class="form-control @error('tanggal_lahir') is-invalid @enderror">
            @error('tanggal_lahir')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="agama" class="form-label">Agama</label>
            <select name="agama" id="agama" class="form-control">
                @foreach(\App\Models\Warga::getAgama() as $data)
                <option @if($warga->agama == $data) selected @endif value="{{ $data }}">{{ $data }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="pekerjaan">pekerjaan</label>
            <input type="text" class="form-control" value="{{ $warga->pekerjaan }}" name="pekerjaan">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="pendidikan">pendidikan</label>
            <input type="text" class="form-control" value="{{ $warga->pendidikan }}" name="pendidikan">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="alamat">alamat</label>
            <textarea type="text" name="alamat" id="alamat" class="form-control">{{ $warga->alamat }}</textarea>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="warga_negara">warga negara</label>
            <input type="text" name="warga_negara" id="warga_negara" class="form-control" value="{{ $warga->warga_negara }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="status_pernikahan">status pernikahan</label>
            <select name="status_pernikahan" id="status_pernikahan" class="form-control">
                <option value="belum menikah">belum menikah</option>
                <option value="menikah">menikah</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="golongan_darah">golongan darah</label>
            <select name="golongan_darah" id="golongan_darah" class="form-control">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="desa_id" class="form-label">Desa</label>
            <select name="desa_id" id="desa_id" value="{{ $warga->desa_id ?? old('desa_id') }}" class="form-control @error('desa_id') is-invalid @enderror">
                @foreach($desas as $desa)
                <option @if($warga->desa_id == $desa->id) selected @endif value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                @endforeach
            </select>
            @error('desa_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="kecamatan_id" class="form-label">Kecamatan</label>
            <select name="kecamatan_id" id="kecamatan_id" value="{{ $warga->kecamatan_id ?? old('kecamatan_id') }}" class="form-control @error('kecamatan_id') is-invalid @enderror">
                @foreach($kecamatans as $kecamatan)
                <option @if($warga->kecamatan_id == $kecamatan->id) selected @endif value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                @endforeach
            </select>
            @error('kecamatan_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="kabupaten_id" class="form-label">Kabupaten</label>
            <select name="kabupaten_id" id="kabupaten_id" value="{{ $warga->kabupaten_id ?? old('kabupaten_id') }}" class="form-control @error('kabupaten_id') is-invalid @enderror">
                @foreach($kabupatens as $kabupaten)
                <option @if($warga->kabupaten_id == $kabupaten->id) selected @endif value="{{ $kabupaten->id }}">{{ $kabupaten->nama_kabupaten }}</option>
                @endforeach
            </select>
            @error('kabupaten_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>