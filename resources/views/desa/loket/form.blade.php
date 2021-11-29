<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="nama">Nama Loket</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $loket->nama ?? old('nama') }}">

            @error('nama')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="kuota">Kuota Antrian</label>
            <input type="number" name="kuota" id="kuota" class="form-control" value="{{ $loket->kuota ?? 20 }}">

            @error('kuota')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>