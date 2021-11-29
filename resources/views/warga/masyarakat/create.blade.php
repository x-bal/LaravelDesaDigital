@extends('layouts.app')
@section('title', 'Masyarakat Create')
@push('bread')
<li class="breadcrumb-item"><a href="{{ route('warga.masyarakat.index') }}">Masyarakat</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ route('warga.masyarakat.index') }} " class="btn btn-sm btn-outline-info"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('warga.masyarakat.store') }}" method="post" id="form">
                    @csrf
                    @include('warga.masyarakat.form')
                </form>
            </div>
            <div class="card-footer d-flex flex-row justify-content-end">
                <button class="btn btn-sm btn-outline-success" id="store"><i class="fas fa-cloud-upload-alt"></i> Store</button>
            </div>
        </div>
    </div>
</div>
@stop
@push('script')
<script>
    $('#store').on('click', function(){
        $('#form').submit()
    });
</script>
@endpush