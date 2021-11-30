@extends('layouts.app')
@section('title', 'Antrian List')
@push('bread')
<li class="breadcrumb-item active">Antrian</li>
@endpush
@section('content')
@if(session('speech'))
{!! session('speech') !!}
@endif
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Antrian Tanggal : {{ request('from') && request('to') ? request('from') . ' s/d '. request('to') : now('Asia/Jakarta')->format('d/m/Y') }}</h5>
            </div>
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-info"><i class="fas fa-arrow-left"></i> Back</a>
                <a href="{{ route('desa.antrian.create') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Create New</a>
            </div>
            <div class="card-body">
                <form action="" method="get" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" name="from" id="from" class="form-control" value="{{ request('from') ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="to" id="to" class="form-control" value="{{ request('to') ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No Antrian</th>
                                <th>Nik</th>
                                <th>Nama</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal Antri</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antrians as $antrian)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <th>{{ $antrian->no_antrian }}</th>
                                <th>{{ $antrian->warga->nik }}</th>
                                <th>{{ $antrian->warga->nama_warga }}</th>
                                <th>{{ $antrian->jenis->jenis_surat }}</th>
                                <th>{{ $antrian->tanggal_antri }}</th>
                                <td>
                                    <div class="btn-group group-{{ $antrian->id }}">
                                        @if($antrian->status == 0 )
                                        <button type="button" data-antri="{{ $antrian->no_antrian }}" data-loket="{{ $antrian->loket->id }}" id="{{ $antrian->id }}" class="btn btn-sm btn-outline-success btn-play play-{{ $antrian->id }}">Panggil</button>
                                        @endif
                                        @if($antrian->status == 1)
                                        <a href="{{ route('desa.antrian.status', $antrian->id) }}" class="btn btn-sm btn-outline-success">Konfirmasi</a>
                                        @endif
                                        <a href="{{ route('desa.antrian.edit', $antrian->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('desa.antrian.destroy', $antrian->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-outline-danger delete_confirm" type="submit"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="audio">
    <audio id="in" src="{{ asset('assets') }}/audio/in.wav"></audio>
    <audio id="out" src="{{ asset('assets') }}/audio/out.wav"></audio>
    <audio id="suarabel" src="{{ asset('assets') }}/audio/Airport_Bell.mp3"></audio>
    <audio id="suarabelnomorurut" src="{{ asset('assets') }}/audio/nomor-urut.MP3"></audio>
    <audio id="suarabelsuarabelloket" src="{{ asset('assets') }}/audio/konter.MP3"></audio>
    <audio id="belas" src="{{ asset('assets') }}/audio/belas.MP3"></audio>
    <audio id="sebelas" src="{{ asset('assets') }}/audio/sebelas.MP3"></audio>
    <audio id="puluh" src="{{ asset('assets') }}/audio/puluh.MP3"></audio>
    <audio id="sepuluh" src="{{ asset('assets') }}/audio/sepuluh.MP3"></audio>
    <audio id="ratus" src="{{ asset('assets') }}/audio/ratus.MP3"></audio>
    <audio id="seratus" src="{{ asset('assets') }}/audio/seratus.MP3"></audio>
    <audio id="suarabelloket1" src="{{ asset('assets') }}/audio/1.MP3"></audio>
    <audio id="suarabelloket2" src="{{ asset('assets') }}/audio/2.MP3"></audio>
    <audio id="suarabelloket3" src="{{ asset('assets') }}/audio/3.MP3"></audio>
    <audio id="suarabelloket4" src="{{ asset('assets') }}/audio/4.MP3"></audio>
    <audio id="suarabelloket5" src="{{ asset('assets') }}/audio/5.MP3"></audio>
    <audio id="suarabelloket6" src="{{ asset('assets') }}/audio/6.MP3"></audio>
    <audio id="suarabelloket7" src="{{ asset('assets') }}/audio/7.MP3"></audio>
    <audio id="suarabelloket8" src="{{ asset('assets') }}/audio/8.MP3"></audio>
    <audio id="suarabelloket9" src="{{ asset('assets') }}/audio/9.MP3"></audio>
    <audio id="suarabelloket10" src="{{ asset('assets') }}/audio/sepuluh.MP3"></audio>
    <audio id="loket" src="{{ asset('assets') }}/audio/loket.MP3"></audio>
</div>
@stop
@push('script')
<script>
    $('#datatable').DataTable()
    $('.delete_confirm').click(function(event) {
        let form = $(this).closest("form");
        event.preventDefault();
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
            .then((willDelete) => {
                if (willDelete.value) {
                    form.submit();
                }
            });
    });

    $(document).ready(function() {
        function mulai(urut, loket) {
            var totalwaktu = 8568.163;
            document.getElementById('in').pause();
            document.getElementById('in').currentTime = 0;
            document.getElementById('in').play();
            totalwaktu = document.getElementById('in').duration * 1000;
            setTimeout(function() {
                document.getElementById('suarabelnomorurut').pause();
                document.getElementById('suarabelnomorurut').currentTime = 0;
                document.getElementById('suarabelnomorurut').play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            if (urut < 10) {
                setTimeout(function() {
                    document.getElementById('suarabel0').pause();
                    document.getElementById('suarabel0').currentTime = 0;
                    document.getElementById('suarabel0').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut == 10) {
                setTimeout(function() {
                    document.getElementById('sepuluh').pause();
                    document.getElementById('sepuluh').currentTime = 0;
                    document.getElementById('sepuluh').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut == 11) {
                setTimeout(function() {
                    document.getElementById('sebelas').pause();
                    document.getElementById('sebelas').currentTime = 0;
                    document.getElementById('sebelas').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut < 20) {
                setTimeout(function() {
                    document.getElementById('suarabel1').pause();
                    document.getElementById('suarabel1').currentTime = 0;
                    document.getElementById('suarabel1').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('belas').pause();
                    document.getElementById('belas').currentTime = 0;
                    document.getElementById('belas').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut < 100) {
                setTimeout(function() {
                    document.getElementById('suarabel0').pause();
                    document.getElementById('suarabel0').currentTime = 0;
                    document.getElementById('suarabel0').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('puluh').pause();
                    document.getElementById('puluh').currentTime = 0;
                    document.getElementById('puluh').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabel1').pause();
                    document.getElementById('suarabel1').currentTime = 0;
                    document.getElementById('suarabel1').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut == 100) {
                setTimeout(function() {
                    document.getElementById('seratus').pause();
                    document.getElementById('seratus').currentTime = 0;
                    document.getElementById('seratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut < 110) {
                setTimeout(function() {
                    document.getElementById('seratus').pause();
                    document.getElementById('seratus').currentTime = 0;
                    document.getElementById('seratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabel2').pause();
                    document.getElementById('suarabel2').currentTime = 0;
                    document.getElementById('suarabel2').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut == 110) {
                setTimeout(function() {
                    document.getElementById('seratus').pause();
                    document.getElementById('seratus').currentTime = 0;
                    document.getElementById('seratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('sepuluh').pause();
                    document.getElementById('sepuluh').currentTime = 0;
                    document.getElementById('sepuluh').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut == 111) {
                setTimeout(function() {
                    document.getElementById('seratus').pause();
                    document.getElementById('seratus').currentTime = 0;
                    document.getElementById('seratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('sebelas').pause();
                    document.getElementById('sebelas').currentTime = 0;
                    document.getElementById('sebelas').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut < 120) {
                setTimeout(function() {
                    document.getElementById('seratus').pause();
                    document.getElementById('seratus').currentTime = 0;
                    document.getElementById('seratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabel2').pause();
                    document.getElementById('suarabel2').currentTime = 0;
                    document.getElementById('suarabel2').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('belas').pause();
                    document.getElementById('belas').currentTime = 0;
                    document.getElementById('belas').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut == 120) {
                setTimeout(function() {
                    document.getElementById('seratus').pause();
                    document.getElementById('seratus').currentTime = 0;
                    document.getElementById('seratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabel1').pause();
                    document.getElementById('suarabel1').currentTime = 0;
                    document.getElementById('suarabel1').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('puluh').pause();
                    document.getElementById('puluh').currentTime = 0;
                    document.getElementById('puluh').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut < 200) {
                setTimeout(function() {
                    document.getElementById('seratus').pause();
                    document.getElementById('seratus').currentTime = 0;
                    document.getElementById('seratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabel1').pause();
                    document.getElementById('suarabel1').currentTime = 0;
                    document.getElementById('suarabel1').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('puluh').pause();
                    document.getElementById('puluh').currentTime = 0;
                    document.getElementById('puluh').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;

                if (urut % 10 != 0) {
                    setTimeout(function() {
                        document.getElementById('suarabel2').pause();
                        document.getElementById('suarabel2').currentTime = 0;
                        document.getElementById('suarabel2').play();
                    }, totalwaktu);
                    totalwaktu = totalwaktu + 1000;
                }

                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut == 200) {
                setTimeout(function() {
                    document.getElementById('suarabel0').pause();
                    document.getElementById('suarabel0').currentTime = 0;
                    document.getElementById('suarabel0').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('ratus').pause();
                    document.getElementById('ratus').currentTime = 0;
                    document.getElementById('ratus').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            } else if (urut < 999) {
                setTimeout(function() {
                    document.getElementById('suarabel0').pause();
                    document.getElementById('suarabel0').currentTime = 0;
                    document.getElementById('suarabel0').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                if (urut.toString().substr(1, 1) == 0 && urut.toString().substr(2, 1) == 0) { // 200 300 400 ..
                    setTimeout(function() {
                        document.getElementById('ratus').pause();
                        document.getElementById('ratus').currentTime = 0;
                        document.getElementById('ratus').play();
                    }, totalwaktu);
                    totalwaktu = totalwaktu + 1000;
                } else if (urut.toString().substr(1, 1) == 0 && urut.toString().substr(2, 1) != 0) { // 201 304 405 506
                    setTimeout(function() {
                        document.getElementById('ratus').pause();
                        document.getElementById('ratus').currentTime = 0;
                        document.getElementById('ratus').play();
                    }, totalwaktu);
                    totalwaktu = totalwaktu + 1000;
                    setTimeout(function() {
                        document.getElementById('suarabel2').pause();
                        document.getElementById('suarabel2').currentTime = 0;
                        document.getElementById('suarabel2').play();
                    }, totalwaktu);
                    totalwaktu = totalwaktu + 1000;
                } else if (urut.toString().substr(1, 1) != 0 && urut.toString().substr(2, 1) == 0) { //210 250 230
                    if (urut.toString().substr(1, 1) == 1) { //210
                        setTimeout(function() {
                            document.getElementById('ratus').pause();
                            document.getElementById('ratus').currentTime = 0;
                            document.getElementById('ratus').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                        setTimeout(function() {
                            document.getElementById('sepuluh').pause();
                            document.getElementById('sepuluh').currentTime = 0;
                            document.getElementById('sepuluh').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                    } else {
                        setTimeout(function() {
                            document.getElementById('ratus').pause();
                            document.getElementById('ratus').currentTime = 0;
                            document.getElementById('ratus').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                        setTimeout(function() {
                            document.getElementById('suarabel1').pause();
                            document.getElementById('suarabel1').currentTime = 0;
                            document.getElementById('suarabel1').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                        setTimeout(function() {
                            document.getElementById('puluh').pause();
                            document.getElementById('puluh').currentTime = 0;
                            document.getElementById('puluh').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                    }
                } else if (urut.toString().substr(1, 1) != 0 && urut.toString().substr(2, 1) != 0) {
                    if (urut.toString().substr(1, 1) == 1) {
                        if (urut.toString().substr(2, 1) == 1) { // 211 311 411 511
                            setTimeout(function() {
                                document.getElementById('ratus').pause();
                                document.getElementById('ratus').currentTime = 0;
                                document.getElementById('ratus').play();
                            }, totalwaktu);
                            totalwaktu = totalwaktu + 1000;
                            setTimeout(function() {
                                document.getElementById('sebelas').pause();
                                document.getElementById('sebelas').currentTime = 0;
                                document.getElementById('sebelas').play();
                            }, totalwaktu);
                            totalwaktu = totalwaktu + 1000;
                        } else { //212 215 219
                            setTimeout(function() {
                                document.getElementById('ratus').pause();
                                document.getElementById('ratus').currentTime = 0;
                                document.getElementById('ratus').play();
                            }, totalwaktu);
                            totalwaktu = totalwaktu + 1000;
                            setTimeout(function() {
                                document.getElementById('suarabel2').pause();
                                document.getElementById('suarabel2').currentTime = 0;
                                document.getElementById('suarabel2').play();
                            }, totalwaktu);
                            totalwaktu = totalwaktu + 1000;
                            setTimeout(function() {
                                document.getElementById('belas').pause();
                                document.getElementById('belas').currentTime = 0;
                                document.getElementById('belas').play();
                            }, totalwaktu);
                            totalwaktu = totalwaktu + 1000;
                        }
                    } else {
                        setTimeout(function() {
                            document.getElementById('ratus').pause();
                            document.getElementById('ratus').currentTime = 0;
                            document.getElementById('ratus').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                        setTimeout(function() {
                            document.getElementById('suarabel1').pause();
                            document.getElementById('suarabel1').currentTime = 0;
                            document.getElementById('suarabel1').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                        setTimeout(function() {
                            document.getElementById('puluh').pause();
                            document.getElementById('puluh').currentTime = 0;
                            document.getElementById('puluh').play();
                        }, totalwaktu);
                        totalwaktu = totalwaktu + 1000;
                        if (urut % 10 != 0) {
                            setTimeout(function() {
                                document.getElementById('suarabel2').pause();
                                document.getElementById('suarabel2').currentTime = 0;
                                document.getElementById('suarabel2').play();
                            }, totalwaktu);
                            totalwaktu = totalwaktu + 1000;
                        }
                    }
                }

                setTimeout(function() {
                    document.getElementById('loket').pause();
                    document.getElementById('loket').currentTime = 0;
                    document.getElementById('loket').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    document.getElementById('suarabelloket' + loket + '').pause();
                    document.getElementById('suarabelloket' + loket + '').currentTime = 0;
                    document.getElementById('suarabelloket' + loket + '').play();
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
                setTimeout(function() {
                    for (var i = 0; i < urut.toString().length; i++) {
                        $("#suarabel" + i + "").remove();
                    };
                }, totalwaktu);
                totalwaktu = totalwaktu + 1000;
            }

            setTimeout(function() {
                document.getElementById('out').pause();
                document.getElementById('out').currentTime = 0;
                document.getElementById('out').play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
        }

        $(".btn-play").on('click', function() {
            let id = $(this).attr('id');
            let urut = $(this).attr('data-antri');
            let loket = $(this).attr('data-loket');
            for (var i = 0; i < urut.toString().length; i++) {
                $(".audio").append('<audio id="suarabel' + i + '" src="{{ asset("assets/audio") }}/' + urut.toString().substr(i, 1) + '.MP3" ></audio>');
                mulai(urut, loket)
            };

            $.ajax({
                url: '/desa/antrian/' + id + '/status',
                method: 'GET',
                type: 'GET',
                success: function(response) {
                    Swal.fire(
                        'Selamat!',
                        response.message,
                        'success'
                    )

                    $('.play-' + id).remove()
                    $(".group-" + id).prepend(`
                    <a href="/desa/antrian/` + id + `/status" class="btn btn-sm btn-outline-success">Konfirmasi</a>`)
                }
            })
        })
    })
</script>

@endpush