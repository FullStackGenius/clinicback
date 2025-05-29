@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="fs-3">Resources</div>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Resources</li>
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
                                <h3 class="card-title">Resources </h3> <a href="{{ route('resources.create') }}"
                                    class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Resources</a>
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
                                            <th style="width: 10px">#</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Short Description</th>
                                            {{-- <th>Long Description</th> --}}
                                            <th>Resource Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resourceData as $resource)
                                            <tr class="align-middle">
                                                <td>

                                                    @php
                                                        $perPage = $resourceData->perPage(); // Number of items per page
                                                        $currentPage = $resourceData->currentPage(); // Current page number
                                                        $startingSerno = ($currentPage - 1) * $perPage; // Calculate starting serial number
                                                    @endphp
                                                    {{ $startingSerno + $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $resource->title }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('resource-category.edit', @$resource->id) }}">{{ @$resource->resourceCategory->name }}
                                                </td>
                                                <td>
                                                    {{ $resource->short_description }}
                                                </td>
                                                {{-- <td>
                                                    {!! $resource->description !!}
                                                </td> --}}
                                                <td>
                                                    @if (!empty($resource->resource_image))
                                                        <img width="50px" height="54px"
                                                            src="{{ $resource->resource_image_path }}">
                                                    @endif
                                                </td>

                                                <td>
                                                    @if (@$resource->status == 1)
                                                        <span class="badge bg-success"><i class="fa fa-check-circle"></i>
                                                            Active</span>
                                                    @endif
                                                    @if (@$resource->status == 0)
                                                        <span class="badge bg-danger"><i class="fa fa-times-circle"></i>
                                                            Inactive</span>
                                                    @endif

                                                </td>
                                                <td style="display: flex;column-gap: 5px;"><a
                                                        href="{{ route('resources.edit', $resource->id) }}"
                                                        class="btn btn-primary btn-sm mb-2"><i class="fa fa-edit"></i>
                                                        Edit</a>
                                                    <form action="{{ route('resources.destroy', $resource->id) }}"
                                                        method="POST" id="submitDeleteForm{{ $resource->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button data-token="{{ $resource->id }}"
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
                                {!! $resourceData->links('pagination::bootstrap-4') !!}
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
