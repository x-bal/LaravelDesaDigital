<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="text" class="form-label">Text Marque</label>
            <input type="text" name="text" id="text" value="{{ $marque->text ?? old('text') }}" class="form-control @error('text') is-invalid  @enderror">
            @error('text')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

</div>