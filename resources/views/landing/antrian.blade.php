@extends('layouts.landing')

@section('content')
<div class="row mt-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center p-5">
                <h2>{{ $no_antri + 1 }}</h2>
                <b>No Antrian</b>
                <br>
                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Daftar
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center p-5">
                <h2>{{ $selesai }}</h2>
                <b>Antrian Selesai</b>
                <br><br><br>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Antrian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('antrian.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="no_antri" value="{{ $no_antri + 1 }}">
                    <div class="form-group">
                        <label for="nik">Nik</label>
                        <input type="number" name="nik" id="nik" class="form-control">

                        @error('nik')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis Surat</label>
                        <select name="jenis" id="jenis" class="form-control">
                            <option disabled selected>-- Pilih Jenis Surat --</option>
                            @foreach($jenis as $jns)
                            <option value="{{ $jns->id }}">{{ $jns->jenis_surat }}</option>
                            @endforeach
                        </select>

                        @error('jenis')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
</div>
@stop