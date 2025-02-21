@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="fs-3">Countries</div>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Skill</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-12">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Countries </h3> <a href="{{ route('country.create') }}"
                                    class="btn btn-primary btn-sm "><i class="fa fa-plus-circle"></i> Countries</a>
                            </div>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px"><i class="fa fa-list-ol"></i></th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($countries as $country)
                                            <tr class="align-middle">
                                                <td>

                                                    @php
                                                        $perPage = $countries->perPage(); // Number of items per page
                                                        $currentPage = $countries->currentPage(); // Current page number
                                                        $startingSerno = ($currentPage - 1) * $perPage; // Calculate starting serial number
                                                    @endphp
                                                    {{ $startingSerno + $loop->iteration }}
                                                </td>

                                                <td>
                                                    {{ $country->name }}
                                                </td>
                                                <td>
                                                    @if (@$country->status == 1)
                                                        <span class="badge bg-success"><i class="fa fa-check-circle"></i> Active</span>
                                                    @endif
                                                    @if (@$country->status == 0)
                                                        <span class="badge bg-danger"><i class="fa fa-times-circle"></i> Inactive</span>
                                                    @endif


                                                </td>
                                                <td style="display: flex;column-gap: 5px;"><a
                                                        href="{{ route('country.edit', $country->id) }}"
                                                        class="btn btn-primary btn-sm mb-2"><i class="fa fa-edit"></i> Edit</a>
                                                    <form action="{{ route('country.destroy', $country->id) }}" method="POST"
                                                        id="submitDeleteForm{{ $country->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button data-token="{{ $country->id }}"
                                                            class="btn btn-danger btn-sm mb-2 confirmDelete"
                                                            type="button"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>

                                            </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {!! $countries->links('pagination::bootstrap-4') !!}
                                {{-- <ul class="pagination pagination-sm m-0 float-end">
                                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                </ul> --}}
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.row -->


                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </main>
    <!-- /.content-wrapper -->
@stop
@push('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 1000);

            $(document).on('click', '.confirmDelete', function() {
                let token = $(this).data('token');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#submitDeleteForm' + token).submit();
                    }
                });
            })

        });
    </script>
@endpush
