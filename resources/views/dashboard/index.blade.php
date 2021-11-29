@extends('layouts.app')
@section('title', 'Beranda')
@push('bread')
<li class="breadcrumb-item active" aria-current="page"><a href="">Dashboard</a></li>
@endpush
@section('content')
@auth
@role('Utama')

@endrole

@role('Kabupaten')

@endrole

@role('Desa')

@endrole
@role('Warga')

@endrole
@endauth
@endsection