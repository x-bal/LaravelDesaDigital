@extends('layouts.app')
@section('title', 'Setting')
@push('bread')
<li class="breadcrumb-item active">Setting</li>
@endpush
@section('content')
<div class="card">
    <div class="card-header">Setting Background Antrian</div>
    <div class="card-body">
        <form action="{{ route('setting.update', $desa->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('storage/' . $desa->background) }}" alt="" class="img-thumbnail border-0 wd-100 wd-sm-200">
                        </div>
                        <div class="col-md-8">
                            <label for="">Background</label><br>
                            <div class="form-group">
                                <input type="file" name="background" id="background">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('storage/' . $desa->logo) }}" alt="" class="img-thumbnail border-0 wd-100 wd-sm-200">
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="">Logo</label><br>
                                <input type="file" name="logo" id="logo">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" class="form-control">{{ $desa->alamat }}</textarea>
                    </div>
                    <button class="btn btn-sm btn-primary" type="submit">Upload</button>
                </div>
            </div>
        </form>
    </div>
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
</script>
@endpush