<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="responses" class="form-label">responses</label>
            <input type="text" name="responses" id="responses" value="{{ $aduan->responses ?? old('responses') }}" class="form-control @error('responses') is-invalid @enderror">
            @error('responses')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>