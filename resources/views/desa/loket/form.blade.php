<div class="form-group">
    <label for="nama">Nama Loket</label>
    <input type="text" name="nama" id="nama" class="form-control" value="{{ $loket->nama ?? old('nama') }}">

    @error('nama')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>