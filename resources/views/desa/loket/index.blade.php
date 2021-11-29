@extends('layouts.app')
@section('title', 'Loket List')
@push('bread')
<li class="breadcrumb-item active">Loket</li>
@endpush
@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-info"><i class="fas fa-arrow-left"></i> Back</a>
                <a href="{{ route('desa.loket.create') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Create New</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Desa</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lokets as $loket)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <th>{{ $loket->nama }}</th>
                                <th>{{ $loket->desa->nama_desa }}</th>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('desa.loket.edit', $loket->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('desa.loket.destroy', $loket->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-outline-danger delete_confirm" type="submit">Destroy</button>
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