@extends('layouts.admin')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="fs-3">Client</div>
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
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-md-12">
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4">
                            <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Client</div>
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
                            <form action="{{ route('client.update', $user->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Body-->
                                <div class="card-body">




                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ @$user->name }}" />
                                        @error('name')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last name</label>
                                        <input type="text" class="form-control" id="name" name="last_name"
                                            value="{{ @$user->last_name }}" />
                                        @error('last_name')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ @$user->email }}" />
                                        @error('email')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="sel1" class="form-label">Status:</label>
                                        <select class="form-control" id="sel1" name="status">
                                            <option value="1" @if (@$user->user_status == 1) selected @endif>Active
                                            </option>
                                            <option value="0" @if (@$user->user_status == 0) selected @endif>
                                                Inactive</option>

                                        </select>
                                        @error('status')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description</label>

                                        <textarea class="form-control" id="short_description" name="short_description">{{ @$resourceData->short_description }}</textarea>
                                        @error('short_description')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    {{-- <div class="mb-3">
                                        <label for="long_description" class="form-label">Long description</label>
                                        <textarea id="summernote" name="long_description">{{ @$resourceData->description }}</textarea>

                                        @error('long_description')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div> --}}









                                    <label for="" class="form-label">Profile Image</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="profile_image"
                                            id="inputGroupFile02">
                                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                    </div>
                                    @if (!empty(@$user->profile_image))
                                        <img width="50px" height="54px" src="{{ @$user->profile_image_path }}">
                                    @endif
                                    @error('profile_image')
                                        <div class="error" style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>




                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('client.index') }}" class="btn btn-dark">Back</a>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                tabsize: 2,
                height: 200
            });
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 1000);
        });
    </script>
@endpush
