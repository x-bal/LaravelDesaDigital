@extends('layouts.app')
@section('title', 'Setting')
@push('bread')
<li class="breadcrumb-item active">Setting</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Setting Background Antrian</div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $desa->background) }}" alt="" class="img-thumbnail wd-100 wd-sm-200">
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('setting.update', $desa->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" name="background" id="background">
                            </div>
                            <button class="btn btn-sm btn-primary" type="submit">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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