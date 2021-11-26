@extends('layouts.app')
@section('title', 'Create Permohonan Surat')
@push('bread')
<li class="breadcrumb-item"><a href="{{ route('desa.permohonan.index') }}">Permohonan</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ route('desa.permohonan.index') }} " class="btn btn-sm btn-info">Back</a>
            </div>
            <form action="{{ route('desa.permohonan.store') }}" method="post" id="form">
                <div class="card-body">
                    @csrf
                    @include('desa.permohonan.form')
                </div>
                <div class="card-footer d-flex flex-row justify-content-end">
                    <button class="btn btn-sm btn-success" id="store">Store</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop