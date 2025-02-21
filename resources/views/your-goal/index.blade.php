@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="fs-3">Your Goal</div>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Your Goal</li>
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
                                <h3 class="card-title">Your Goal </h3> 
                                {{-- <a href="{{ route('your-goal.create') }}"
                                    class="btn btn-primary btn-sm ">Create Goal</a> --}}
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
                                            <th>Name</th>
                                            <th>Icon</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($yourGoals as $yourGoal)
                                            <tr class="align-middle">
                                                <td>

                                                    @php
                                                        $perPage = $yourGoals->perPage(); // Number of items per page
                                                        $currentPage = $yourGoals->currentPage(); // Current page number
                                                        $startingSerno = ($currentPage - 1) * $perPage; // Calculate starting serial number
                                                    @endphp
                                                    {{ $startingSerno + $loop->iteration }}
                                                </td>

                                                <td>
                                                    {{ $yourGoal->name }}
                                                </td>
                                                <td><img width="50px" height="54px" src="{{ $yourGoal->icon_image_path }}"></td>
                                                <td>
                                                    @if ($yourGoal->status == 1)
                                                        
                                                    <span class="badge bg-success"><i class="fa fa-check-circle"></i> Active</span>
                                                @endif
                                                @if ($yourGoal->status == 0)
                                                     <span class="badge bg-danger"><i class="fa fa-times-circle"></i> Inactive</span>
                                                @endif
                                                  

                                                </td>
                                                <td style="display: flex;column-gap: 5px;"><a
                                                        href="{{ route('your-goal.edit', $yourGoal->id) }}"
                                                        class="btn btn-primary btn-sm mb-2"><i class="fa fa-edit"></i> Edit</a>
                                                    {{-- <form id="submitDeleteForm{{ $yourGoal->id }}" action="{{ route('your-goal.destroy', $yourGoal->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button data-token="{{ $yourGoal->id }}" class="btn btn-danger btn-sm mb-2 confirmDelete" type="button"
                                                            >Delete</button>
                                                    </form> --}}

                                            </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {!! $yourGoals->links('pagination::bootstrap-4') !!}
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
@endsection
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