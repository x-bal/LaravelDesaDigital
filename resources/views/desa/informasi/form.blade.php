<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="judul" class="form-label">judul</label>
            <input type="text" name="judul" id="judul" value="{{ $informasi->judul ?? old('judul') }}" class="form-control @error('judul') is-invalid  @enderror">
            @error('judul')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="deskripsi" class="form-label">deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid  @enderror">{{ $informasi->deskripsi ?? old('deskripsi') }}</textarea>
            @error('deskripsi')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="desa_id" class="form-label">desa id</label>
            <select name="desa_id" id="desa_id" class="form-control @error('desa_id') is-invalid  @enderror">
                @foreach($desas as $desa)
                <option @if($informasi->desa_id ?? old('desa_id') == $desa->id) selected @endif value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
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
            <label for="kategori_informasi_id" class="form-label">kategori informasi id</label>
            <select name="kategori_informasi_id" id="kategori_informasi_id" class="form-control @error('kategori_informasi_id') is-invalid  @enderror">
                @foreach($kategori_informasis as $data)
                <option @if($informasi->kategori_informasi_id ?? old('kategori_informasi_id') == $data->id) selected @endif value="{{ $data->id }}">{{ $data->nama }}</option>
                @endforeach
            </select>
            @error('kategori_informasi_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>