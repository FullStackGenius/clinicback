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
                        <div class="fs-3">Job Details</div>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Job Details</li>
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
                                <div class="card-title">Job Details</div>
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
                            <form action="{{ route('jobs.update', $project->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--begin::Body-->
                                <div class="card-body">




                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ @$project->title }}" />
                                        @error('title')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="mb-3">
                                        <label for="projectType" class="form-label">How can we help you get started
                                            ?:</label>
                                        <select class="form-control" id="projectType" name="projectType">
                                            @foreach ($projectTypes as $projectType)
                                            <option value="" >Select option</option>
                                                <option value="{{ $projectType->id }}"
                                                    @if (@$project->project_type_id == $projectType->id) selected @endif>
                                                    {{ $projectType->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                        @error('projectType')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="sel1" class="form-label">Accounting Skills:</label>
                                        <select class="form-control" id="sel1" name="skills[]" multiple
                                            style="width: 100%; height: 250px;">
                                            @foreach ($skills as $skill)
                                                <option value="{{ $skill->id }}"
                                                    @if (isset($project) && $project->projectSubCategory->pluck('id')->contains((int) $skill->id)) selected @endif>{{ $skill->name }}
                                                </option>
                                            @endforeach


                                        </select>
                                        <small class="form-text text-muted">Note : <strong>Ctrl</strong> + click to select
                                            multiple Accounting skills.</small>

                                        @error('skills')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="sectors" class="form-label">Accounting Sectors:</label>
                                        <select class="form-control" id="sectors" name="sectors[]" multiple
                                            style="width: 100%; height: 200px;">
                                            @foreach ($sectors as $sector)
                                                <option value="{{ $sector->id }}"
                                                    @if (isset($project) && $project->projectCategory->pluck('id')->contains((int) $sector->id)) selected @endif>{{ $sector->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <small class="form-text text-muted">Note : <strong>Ctrl</strong> + click to select
                                            multiple Accounting Sectors.</small>
                                        @error('sectors')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="certifications" class="form-label">Accounting Certifications:</label>
                                        <select class="form-control" id="certifications" name="certifications[]" multiple
                                            style="width: 100%; height: 200px;">
                                            @foreach ($certifications as $certification)
                                                <option value="{{ $certification->id }}"
                                                    @if (isset($project) && $project->projectSubCategory->pluck('id')->contains((int) $certification->id)) selected @endif>
                                                    {{ $certification->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <small class="form-text text-muted">Note : <strong>Ctrl</strong> + click to select
                                            multiple Accounting Certifications.</small>
                                        @error('certifications')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="projectExperience" class="form-label">What level experience you
                                            need?</label>
                                        <select class="form-control" id="projectExperience" name="projectExperience">
                                            <option value="" >Select option</option>
                                            @foreach ($projectExperiences as $projectExperience)
                                                <option value="{{ $projectExperience->id }}"
                                                    @if (@$project->project_experience_id == $projectExperience->id) selected @endif>
                                                    {{ $projectExperience->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                        @error('projectExperience')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="projectDuration" class="form-label">What is the estimated duration of
                                            your project?:</label>
                                        <select class="form-control" id="projectDuration" name="projectDuration">
                                            <option value="" >Select option</option>
                                            @foreach ($projectDurations as $projectDuration)
                                                <option value="{{ $projectDuration->id }}"
                                                    @if (@$project->project_duration_id == $projectDuration->id) selected @endif>
                                                    {{ $projectDuration->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                        @error('projectDuration')
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

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea id="summernote" name="description">{{ @$project->description }}</textarea>

                                        @error('description')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="status" class="form-label">Project status:</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="" >Select option</option>
                                            <option value="1" @if (@$project->project_status == 1) selected @endif>
                                                Pending
                                            </option>
                                            <option value="2" @if (@$project->project_status == 2) selected @endif>
                                                Drafted</option>
                                            <option value="3" @if (@$project->project_status == 3) selected @endif>
                                                Published</option>
                                            <option value="4" @if (@$project->project_status == 4) selected @endif>
                                                Closed</option>
                                            <option value="5" @if (@$project->project_status == 5) selected @endif>
                                                Assigned</option>


                                        </select>
                                        @error('status')
                                            <div class="error" style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>







                                </div>




                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('freelancer.index') }}" class="btn btn-dark">Back</a>
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
