@extends('layouts.admin')

@section('content')
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-6">
                        <h2 class="fs-2 fw-bold">Freelance Management</h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Freelance</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-lg rounded-3 mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                <h3 class="card-title mb-0">Freelancer List</h3>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success mt-3 mx-3">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger mt-3 mx-3">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover table-striped align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>Profile Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                                <td>{{ ucfirst($user->name . ' ' . $user->last_name) }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ ucfirst(@$user->role->name) }}</td>
                                                <td>
                                                    <img class="rounded-circle" width="50px" height="50px" src="{{ $user->profile_image_path }}" alt="Profile">
                                                </td>
                                                <td>
                                                    @if ($user->user_status == 1)
                                                        <span class="badge bg-success changeYourStatus text-white cursor-pointer" data-url="{{ route('change-status', $user->id) }}"><i class="fa fa-check-circle"></i> Active</span>
                                                    @else
                                                        <span class="badge bg-danger changeYourStatus text-white cursor-pointer" data-url="{{ route('change-status', $user->id) }}"><i class="fa fa-times-circle"></i> Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('freelancer.show', $user->id) }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>

                                                        <a href="{{ route('freelancer.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('freelancer.destroy', $user->id) }}" method="POST" id="deleteUser{{ $user->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger btn-sm deleteUserButton" type="button" data-id="{{ $user->id }}">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer clearfix">
                                <nav aria-label="Pagination">
                                    {!! $users->links('pagination::bootstrap-4') !!}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.changeYourStatus', function() {
                Swal.fire({
                    title: "Are you sure you want to change the status?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, change it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = $(this).data('url');
                        window.location.href = url;
                    }
                });
            });

            $(document).on('click', '.deleteUserButton', function() {
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
                        let id = $(this).data('id');
                        $('#deleteUser' + id).submit();
                    }
                });
            });

            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        });
    </script>
@endpush
