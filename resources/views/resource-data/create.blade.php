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
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4">
                            <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Resources</div>
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
                            <form action="{{ route('resources.store') }}" method="post" enctype="multipart/form-data" >
                                @csrf
                                <!--begin::Body-->
                                <div class="card-body">

                                    <div class="mb-3">
                                        <label for="sel1" class="form-label">Category:</label>
                                        <select class="form-control" id="sel1" name="category">
                                            @foreach($resourceCategorys as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="name" name="title" />
                                        @error('title')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description</label>
                                       
                                        <textarea class="form-control" id="short_description" name="short_description"></textarea>
                                        @error('short_description')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="long_description" class="form-label">Long description</label>
                                        <textarea class="form-control" id="summernote" name="long_description"></textarea>
                                        
                                        @error('long_description')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>



                               

                              
                                <div class="mb-3">
                                    <label for="sel1" class="form-label">Status:</label>
                                    <select class="form-control" id="sel1" name="status">
                                        <option value="1" >Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="error" style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>


                                <label for="" class="form-label">Resource Image</label>
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="resource_image" id="inputGroupFile02">
                                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                </div>
                                @error('resource_image')
                                    <div class="error" style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                          
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('subcategory.index') }}" class="btn btn-dark">Back</a>
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