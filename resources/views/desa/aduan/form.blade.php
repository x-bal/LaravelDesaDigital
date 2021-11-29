<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="respon" class="form-label">respon</label>
            <input type="text" name="respon" id="respon" value="{{ $aduan->respon ?? old('respon') }}" class="form-control @error('respon') is-invalid @enderror">
            @error('respon')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>