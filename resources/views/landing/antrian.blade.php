@extends('layouts.landing', ['title' => $title])
@section('css')
<style>
    body {
        background-image: url("{{ asset('storage/' . App\Models\Desa::find(1)->background) }}");
        background-size: cover;
    }
</style>
<style>
    :root {
        --marquee-width: 80vw;
        --marquee-height: 3vh;
        /* --marquee-elements: 12; */
        /* defined with JavaScript */
        --marquee-elements-displayed: 5;
        --marquee-element-width: calc(var(--marquee-width) / var(--marquee-elements-displayed));
        --marquee-animation-duration: calc(var(--marquee-elements) * 3s);
    }

    .marquee {
        width: var(--marquee-width);
        height: var(--marquee-height);
        /* background-color: #eee; */
        color: #111;
        overflow: hidden;
        position: relative;
    }

    .marquee:before,
    .marquee:after {
        position: absolute;
        top: 0;
        width: 1rem;
        height: 100%;
        content: "";
        z-index: 1;
    }

    /* .marquee:before {
        left: 0;
        background: linear-gradient(to right, #111 0%, transparent 100%);
    }

    .marquee:after {
        right: 0;
        background: linear-gradient(to left, #111 0%, transparent 100%);
    } */

    .marquee-content {
        list-style: none;
        height: 100%;
        display: flex;
        animation: scrolling var(--marquee-animation-duration) linear infinite;
    }

    /* .marquee-content:hover {
  animation-play-state: paused;
} */
    @keyframes scrolling {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(calc(-1 * var(--marquee-element-width) * var(--marquee-elements)));
        }
    }

    .marquee-content li {
        display: flex;
        justify-content: center;
        align-items: center;
        /* text-align: center; */
        flex-shrink: 0;
        /* width: var(--marquee-element-width); */
        max-height: 100%;
        /* font-size: calc(var(--marquee-height)*3/4); */
        /* 5rem; */
        white-space: nowrap;
    }

    .marquee-content li img {
        width: 100%;
        /* height: 100%; */
        border: 2px solid #eee;
    }

    /* @media (max-width: 600px) {
        html {
            font-size: 12px;
        }

        :root {
            --marquee-width: 100vw;
            --marquee-height: 16vh;
            --marquee-elements-displayed: 3;
        }

        .marquee:before,
        .marquee:after {
            width: 5rem;
        }
    } */
</style>
@stop
@section('content')
<!-- <div class="row mt-5">
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
</div> -->

<div class="row d-flex justify-content-between my-4">
    <div class="col-md-4">
        @foreach($loket as $lkt)
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <div class="">
                            @if($lkt->antrian()->where('tanggal_antri', now()->format('Y-m-d'))->count() > $lkt->kuota)
                            <p>*Nomor antrian telah habis</p>
                            @else
                            <div>
                                <h3 class="">
                                    <span class="text-primary">{{ $lkt->nama }}</span>
                                    {{ number_format($lkt->antrian()->where('tanggal_antri', now()->format('Y-m-d'))->count() + 1, 0, ",",".") }}
                                </h3>
                            </div>
                            <b>Nomor Antrian</b>
                            <br><br>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-antri{{ $lkt->id }}">
                                Daftar
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="col-md-8">

    </div>
</div>

<div class=" row mb-5">
    <div class="col-md-12">
        <div class="bg-white p-3 rounded">
            <div class="marquee">
                <ul class="marquee-content">
                    @foreach($marques as $marque)
                    <li><b> {{ $marque->marque }} &bull; </b></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row d-flex justify-content-between my-4">
    @foreach($loket as $done)
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-body text-center">
                <div class="">
                    <h2 class="">{{ $done->antrian()->where('tanggal_antri', now()->format('Y-m-d'))->where('status', 2)->count() }}</h2>
                    <h3 class="">{{ $done->nama }}</h3>
                    <b>Antrian Selesai</b>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div> -->

@foreach($loket as $lkt)
<div class="modal fade" id="modal-antri{{ $lkt->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Antrian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('antrian.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="no_antri" value="{{ $lkt->antrian()->where('tanggal_antri', now()->format('Y-m-d'))->count() + 1 }}">
                    <input type="hidden" name="loket_id" value="{{ $lkt->id }}">
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
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@stop

@section('footer')
<!-- <script>
    var player = document.getElementById("player")


    function play(video) {
        player.src = video
    }

    $("#player").on('ended', function() {
        var source = $("#player").attr('data-id')
        $.ajax({
            url: '/video/' + source,
            method: 'GET',
            type: 'GET',
            success: function(response) {
                $("#player").removeAttr("data-id")
                $("#player").attr("data-id", response.id)
                play("{{ asset('storage') }}/" + response.video)
            }
        })
    })
</script>

<script>
    const root = document.documentElement;
    const marqueeElementsDisplayed = getComputedStyle(root).getPropertyValue("--marquee-elements-displayed");
    const marqueeContent = document.querySelector("ul.marquee-content");

    root.style.setProperty("--marquee-elements", marqueeContent.children.length);

    for (let i = 0; i < marqueeElementsDisplayed; i++) {
        marqueeContent.appendChild(marqueeContent.children[i].cloneNode(true));
    }
</script> -->
@stop