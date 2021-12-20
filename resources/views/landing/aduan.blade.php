@extends('layouts.landing', ['title' => $title])

@section('content')
<div class="row mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Aduan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('aduan.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nik">Nik</label>
                        <input type="number" name="nik" id="nik" class="form-control">

                        @error('nik')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="aduan">Aduan</label>
                        <textarea name="aduan" id="aduan" rows="5" class="form-control"></textarea>

                        @error('aduan')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop