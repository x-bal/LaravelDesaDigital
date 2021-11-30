@extends('layouts.app')
@section('title', 'Pengguna List')
@push('bread')
<li class="breadcrumb-item active">Pengguna</li>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-row justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-info"><i class="fas fa-long-arrow-alt-left"></i> <span>Back</span></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIk</th>
                                <th>Name</th>
                                <td>Email</td>
                                <td>Verifikasi</td>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penggunas as $data)
                                @foreach($data->user as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td>{{ $data->nama_warga }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>{{ $row->email_verified_at }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <form action="{{ route('desa.pengguna.destroy', $data->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-danger delete_confirm" type="submit"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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