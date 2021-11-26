@extends('layouts.app')
@section('title', 'Create Antrian')
@push('bread')
<li class="breadcrumb-item"><a href="{{ route('desa.antrian.index') }}">Antrian</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ route('desa.antrian.index') }} " class="btn btn-sm btn-info">Back</a>
            </div>
            <form action="{{ route('desa.antrian.store') }}" method="post" id="form">
                <div class="card-body">
                    @csrf
                    @include('desa.antrian.form')
                </div>
                <div class="card-footer d-flex flex-row justify-content-end">
                    <button class="btn btn-sm btn-success" id="store">Store</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop