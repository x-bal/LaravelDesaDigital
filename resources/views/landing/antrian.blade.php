@extends('layouts.landing')

@section('content')
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body text-center p-5">
                @if($sisaloket != 0)
                <h2>{{ $no_antri + 1 }}</h2>
                <b>No Antrian</b>
                <br>
                <button type="button" class="btn btn-primary mt-5 mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Daftar
                </button>
                <p class="text-secondary">*Nomor antrian anda otomatis akan diarahkan ke loket yang tersedia.</p>
                @else
                <p class="text-secondary">*Nomor antrian telah habis, silahkan datang kembali.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row d-flex justify-content-between my-3">
    @foreach($loket as $lkt)
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body text-center">
                <div class="">
                    <h2 class="">{{ $lkt->antrian()->where('tanggal_antri', now()->format('Y-m-d'))->where('status', 2)->count() }}</h2>
                    <h3 class="">{{ $lkt->nama }}</h3>
                    <b>Antrian Selesai</b>
                </div>
            </div>
        </div>
    </div>
    @endforeach
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