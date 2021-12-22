@extends('layouts.landing', ['title' => $title])
@section('css')
<style>
    body {
        background-image: url("{{ asset('storage/' . App\Models\Desa::find(1)->background) }}");
        background-size: cover;
    }
</style>
@stop
@section('content')
<h2 class="text-white text-center text-shadow mt-5">Puaskah anda dengan pelayanan kami ?</h2>
<div class="row mt-3 d-flex justify-content-center">
    @foreach($rates as $rate)
    <div class="col-md-2">
        <a href="{{ route('penilaian.store', $rate->id) }}" style="text-decoration: none;" onclick="return confirm('Anda yakin dengan penilaian anda ?')">
            <div class="card shadow" style="background-color: rgba(255, 255, 255, .9);">
                <div class="card-body text-center" style="min-height: 9rem;">
                    <img src="{{ asset('storage/' . $rate->icon) }}" alt="" width="100">
                </div>
                <h5 class="text-center text-dark">{{ $rate->name }}</h5>
            </div>
        </a>
    </div>
    @endforeach
</div>
@stop