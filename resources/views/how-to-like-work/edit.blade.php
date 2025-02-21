@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="fs-3">Edit Information</div>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Your Like To work</li>
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
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4">
                            <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Your Like To work</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Form-->

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
                            <form action="{{ route('how-to-like-work.update', $howLikeToWork->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Body-->
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $howLikeToWork->name }}" />
                                        @error('name')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" name="description"
                                            value="{{ $howLikeToWork->description }}" />
                                        @error('description')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="input-group mb-3">

                                        <input type="file" class="form-control" name="icon_image" id="inputGroupFile02">
                                        <label class="input-group-text" for="inputGroupFile02">Upload</label>

                                    </div>
                                    @if(!empty($howLikeToWork->icon_image))
                                    <img width="50px" height="54px" src="{{ $howLikeToWork->icon_image_path }}">
                                    @endif
                                    @error('icon_image')
                                        <div class="error" style="color: red;">{{ $message }}</div>
                                    @enderror

                                    <div class="mb-3">
                                        <label for="sel1" class="form-label">Select list:</label>
                                        <select class="form-control" id="sel1" name="status">
                                            <option value="1" @if ($howLikeToWork->status == 1) selected @endif>Active
                                            </option>
                                            <option value="0" @if ($howLikeToWork->status == 0) selected @endif>
                                                Inactive</option>

                                        </select>
                                        {{-- <label for="name" class="form-label">Name</label>
                            <input
                              type="text"
                              class="form-control"
                              id="name"
                              name="name"
                              value="{{ $howLikeToWork->name}}"
                             
                            /> --}}
                                        @error('name')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('how-to-like-work.index') }}" class="btn btn-dark">Back</a>
                                </div>
                                <!--end::Footer-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Quick Example-->

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
        });
    </script>
@endpush