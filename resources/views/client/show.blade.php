@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h3 class="text-2xl font-semibold">Client Profile</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Client Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-lg rounded-lg mb-5">
                            {{-- <div class="card-header flex justify-between items-center bg-gray-100 py-4 px-6">
                                <h3 class="card-title font-medium text-lg">Client Details</h3>
                                <a href="{{ route('client.index') }}" class="btn btn-dark btn-sm">Back</a>
                            </div> --}}
                            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                <h3 class="card-title mb-0">Client Details</h3>
                                <a href="{{ route('client.index') }}" class="btn btn-dark btn-sm">Back To Clients</a>
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

                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-3 px-4">#</th>
                                            <th class="py-3 px-4">Information</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Name</th>
                                            <td class="py-3 px-4">{{ $client->name." ".$client->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Email</th>
                                            <td class="py-3 px-4">{{ $client->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Type</th>
                                            <td class="py-3 px-4">{{ ucfirst(@$client->role->name ) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Profile Image</th>
                                            <td class="py-3 px-4">
                                                <img class="rounded-lg shadow-sm" width="50px" height="54px" src="{{ $client->profile_image_path }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Status</th>
                                            <td class="py-3 px-4">
                                                @if ($client->user_status == 1)
                                                    <span class="badge bg-success changeYourStatus cursor-pointer" data-url="{{ route('change-status', $client->id) }}" title="Click to Inactive user"><i class="fa fa-check-circle"></i> Active</span>
                                                @endif
                                                @if ($client->user_status == 0)
                                                    <span class="badge bg-danger changeYourStatus cursor-pointer" data-url="{{ route('change-status', $client->id) }}" title="Click to active user"><i class="fa fa-times-circle"></i> Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Country</th>
                                            <td class="py-3 px-4">{{ @$client->country->name}}</td>
                                        </tr>
                                    </tbody>
                                </table>
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
        $(document).ready(function () {
            $(document).on('click', '.changeYourStatus', function () {
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

            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 1000);
        });
    </script>
@endpush