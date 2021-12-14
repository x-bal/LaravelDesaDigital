<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="video" class="form-label">Video</label>
            <input type="file" name="video" id="video">

            @error('video')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>