<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="nama" class="form-label">nama</label>
            <input type="text" name="nama" id="nama" value="{{ $kategori_informasi->nama ?? old('nama') }}" class="form-control @error('nama') is-invalid  @enderror">
            @error('nama')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>