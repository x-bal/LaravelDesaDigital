<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="desa_id">desa</label>
            <select name="desa_id" id="desa_id" class="form-control @error('desa_id') is-invalid @enderror">
                @foreach($desas as $desa)
                <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                @endforeach
            </select>
            @error('desa_id')
            <span class="invalid-feedback">
            {{ $message }}
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="photo">photo</label>
            <input type="file" name="photo[]" multiple id="photo" class="form-control @error('photo') is-invalid @enderror">
            @error('photo')
            <span class="invalid-feedback">
                {{ $message }}
            </span>
            @enderror
        </div>
    </div>
</div>