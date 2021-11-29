@extends('layouts.app')
@section('title', 'Create Loket')
@push('bread')
<li class="breadcrumb-item"><a href="{{ route('desa.loket.index') }}">Loket</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ route('desa.loket.index') }} " class="btn btn-sm btn-info">Back</a>
            </div>
            <form action="{{ route('desa.loket.store') }}" method="post" id="form">
                <div class="card-body">
                    @csrf
                    @include('desa.loket.form')
                </div>
                <div class="card-footer d-flex flex-row justify-content-end">
                    <button class="btn btn-sm btn-success" id="store">Store</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop