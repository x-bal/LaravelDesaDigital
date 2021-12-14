<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" value="{{ $rate->name ?? old('name') }}" class="form-control @error('name') is-invalid  @enderror">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="icon" class="form-label">Rate Icon</label>
            <input type="file" name="icon" id="icon">

            @error('icon')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>