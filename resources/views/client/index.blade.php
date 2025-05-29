@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="fs-3 fw-bold">Client Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Client</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4 shadow rounded-2">
                            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                <h3 class="card-title mb-0">Client List</h3>
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
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped align-middle">
                                        <thead class="table-light">
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
                                            @foreach ($clinetLists as $clinetList)
                                                <tr class="align-middle">
                                                    <td>
                                                        @php
                                                            $perPage = $clinetLists->perPage();
                                                            $currentPage = $clinetLists->currentPage();
                                                            $startingSerno = ($currentPage - 1) * $perPage;
                                                        @endphp
                                                        {{ $startingSerno + $loop->iteration }}
                                                    </td>
                                                    <td>{{ ucfirst($clinetList->name . ' ' . $clinetList->last_name) }}</td>
                                                    <td>{{ $clinetList->email }}</td>
                                                    <td>{{ ucfirst(@$clinetList->role->name) }}</td>
                                                    <td>
                                                        <img src="{{ $clinetList->profile_image_path }}" alt="Profile Image" class="rounded-circle" width="50px" height="50px">
                                                    </td>
                                                    <td>
                                                        @if ($clinetList->user_status == 1)
                                                            <span class="badge bg-success changeYourStatus" style="cursor: pointer;" data-url="{{ route('change-status', $clinetList->id) }}"><i class="fa fa-check-circle"></i> Active</span>
                                                        @else
                                                            <span class="badge bg-danger changeYourStatus" style="cursor: pointer;" data-url="{{ route('change-status', $clinetList->id) }}"><i class="fa fa-times-circle"></i> Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="d-flex gap-2">
                                                        <a href="{{ route('client.show', $clinetList->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-eye"></i> View</a>
                                                        <a href="{{ route('client.edit', $clinetList->id) }}" class="btn btn-success btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a href="{{ route('jobs.index', $clinetList->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-briefcase"></i> View Jobs</a>
                                                        
                                                        <form id="deleteUser{{ $clinetList->id }}" action="{{ route('client.destroy', $clinetList->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button data-id="{{ $clinetList->id }}" class="btn btn-sm btn-danger deleteUserButton" type="button"><i class="fas fa-trash"></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                {!! $clinetLists->links('pagination::bootstrap-4') !!}
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
            }, 1000);
        });
    </script>
@endpush
