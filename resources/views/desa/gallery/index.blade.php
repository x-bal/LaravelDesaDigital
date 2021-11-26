@extends('layouts.app')
@section('title', 'Gallery List')
@push('bread')
<li class="breadcrumb-item active">Gallery</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-info"><i class="fas fa-long-arrow-alt-left"></i> <span>Back</span></a>
                <a href="{{ route('desa.gallery.create') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> <span>add</span></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>desa</th>
                                <th>photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($galleries as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->desa->nama_desa }}</td>
                                <td><img src="{{ asset('storage/'.$data->photo) }}" class="img-thumbnail wd-100p wd-sm-200" alt=""></td>
                                <td>
                                    <div class="btn-group">
                                        <a target="_blank" href="{{ asset('storage/'.$data->photo) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-eye"></i> Show</a>
                                        <form action="{{ route('desa.gallery.destroy', $data->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-outline-danger delete_confirm" type="submit"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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