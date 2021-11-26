<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">nama produk</label>
            <input type="text" name="nama_produk" id="nama_produk" value="{{ $produk->nama_produk ?? old('nama_produk') }}" class="form-control @error('nama_produk') is-invalid @enderror">
            @error('nama_produk')
            <span class="invalid-feedback">
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">harga</label>
            <input type="number" name="harga" id="harga" value="{{ $produk->harga ?? old('harga') }}" class="form-control @error('harga') is-invalid @enderror">
            @error('harga')
            <span class="invalid-feedback">
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">deskripsi</label>
            <input type="text" name="deskripsi" id="deskripsi" value="{{ $produk->deskripsi ?? old('deskripsi') }}" class="form-control @error('deskripsi') is-invalid @enderror">
            @error('deskripsi')
            <span class="invalid-feedback">
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="photo">photo</label>
            <input type="file" multiple name="photo[]" id="photo" class="form-control @error('photo') is-invalid @enderror">
            @error('photo')
            <span class="invalid-feedback">
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">desa</label>
            <select name="desa_id" id="desa_id" class="form-control @error('desa_id') is-invalid @enderror">
                @foreach($desas as $desa)
                <option value="{{ $desa->id }}" @if($produk->desa_id ?? old('desa_id') == $desa->id) selected @endif>{{ $desa->nama_desa }}</option>
                @endforeach
            </select>
            @error('desa_id')
            <span class="invalid-feedback">
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>
</div>