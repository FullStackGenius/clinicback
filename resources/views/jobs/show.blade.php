@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="fs-3">Jobs Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Project Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <!-- Project Details Card -->
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

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            {{-- <div class="card-header">
                                <h3 class="card-title"><b>{{ ucfirst($jobDetails->title) }}</b></h3>
                            </div> --}}
                            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                <h3 class="card-title mb-0"><b>{{ ucfirst($jobDetails->title) }}</b></h3>
                                <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">Back to Jobs</a>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Description:</strong>
                                    <p>{!! $jobDetails->description !!}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Client Name:</strong>
                                    <p>{{ ucfirst($jobDetails->clientUser->name) . ' ' . $jobDetails->clientUser->last_name }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong>Created At:</strong>
                                    <p>{{ $jobDetails->created_at->format('d M, Y H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Budget Type:</strong>
                                    <p>
                                        @if ($jobDetails->budget_type == 1)
                                            <span class="badge bg-success">Hourly</span>
                                            </br>
                                            {{ !empty($jobDetails->hourly_from) ? "$" : '' }}{{ $jobDetails->hourly_from }}
                                            -
                                            {{ !empty($jobDetails->hourly_to) ? "$" : '' }}{{ $jobDetails->hourly_to }}
                                        @elseif($jobDetails->budget_type == 2)
                                            <span class="badge bg-success">Fixed</span>
                                            </br>
                                            {{ !empty($jobDetails->fixed_rate) ? "$" : '' }}{{ $jobDetails->fixed_rate }}
                                        @endif


                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong>Status:</strong>
                                    <p>
                                        @if ($jobDetails->project_status == 1)
                                            <span class="badge bg-success">pending</span>
                                        @endif
                                        @if ($jobDetails->project_status == 2)
                                            <span class="badge bg-success">draft</span>
                                        @endif
                                        @if ($jobDetails->project_status == 3)
                                            <span class="badge bg-success">publish</span>
                                        @endif
                                        @if ($jobDetails->project_status == 4)
                                            <span class="badge bg-success">closed</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <strong>Project skills:</strong>
                                    <p>
                                        @php
                                            $projectSkills = $jobDetails->projectSkill;
                                            $skills = $projectSkills->pluck('name')->implode(' , ');
                                        @endphp

                                        {{ !empty($skills) ? $skills : 'not defined' }}
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <strong>Project Category:</strong>
                                    <p>
                                        @php
                                            $projectCategory = $jobDetails->projectCategory;
                                            $projectsector = $projectCategory->pluck('name')->implode(' , ');
                                        @endphp
                                        {{ !empty($projectsector) ? $projectsector : 'not defined' }}
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <strong>Project Subcategory:</strong>
                                    <p>
                                        @php
                                            $projectSubCategory = $jobDetails->projectSubCategory;
                                            $projectSubCategoryData = $projectSubCategory
                                                ->pluck('name')
                                                ->implode(' , ');
                                        @endphp

                                        {{ !empty($projectSubCategoryData) ? $projectSubCategoryData : 'not defined' }}
                                    </p>
                                </div>

                                @if($jobDetails->contracts)
                                <div class="mb-3">
                                    <strong>Assigned Freelancer:</strong>
                                    <p>
                                        @if($jobDetails?->contracts?->proposal?->freelancerUser)
                                        <a href="{{ route('freelancer.show',$jobDetails->contracts->proposal->freelancerUser->id)}}">{{ $jobDetails->contracts->proposal->freelancerUser->name }} {{ $jobDetails->contracts->proposal->freelancerUser->last_name }}</a>
                                        @endif
                                    </p>
                                </div>
                                @endif

                                


                                {{-- <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back to Jobs</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </main>
    <!-- /.content-wrapper -->
    <!--- assign model ---->
    <!-- The Modal -->
    {{-- <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                
                <div class="modal-header">
                    <h4 class="modal-title">Assign Job To Freelancer</h4>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>



                </div>

              
                <div class="modal-body">
                    <form action="{{ route('jobs.assign-job') }}" method="POST">
                        @csrf
                        <div class="mb-3 mt-3">
                            <label for="freelancerList">Freelancers:</label>
                         
                            <select class="form-control form-select" id="freelancerList" name="freelancer" required>
                                <option value="">Select Freelancer</option>
                                @foreach ($freelancers as $freelancer)
                                    <option value="{{ $freelancer->id }}">
                                        {{ $freelancer->name . ' ' . $freelancer->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="project_start_date">Project start Date:</label>
                            <input required type="datetime-local" class="form-control" id="project_start_date"
                                placeholder="Project start Date" name="project_start_date">
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="project_end_date">Project end Date:</label>
                            <input required type="datetime-local" class="form-control" id="project_end_date"
                                placeholder="Project end Date" name="project_end_date">
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="contract_type">Contract Type:</label>
                            <select class="form-control form-select" id="contract_type" name="contract_type" required>
                                <option value="">Select</option>
                                <option value="hourly">hourly</option>
                                <option value="fixed">fixed</option>
                            </select>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="payment_type">Payment Type:</label>
                            <select class="form-control form-select" id="payment_type" name="payment_type" required>
                                <option value="">Select</option>
                                <option value="milestone">milestone</option>
                                <option value="lump_sum">lump_sum</option>
                            </select>
                        </div>


                        <div class="mb-3 mt-3">
                            <label for="amount">Project amount:</label>
                            <input type="text" class="form-control" id="amount" placeholder="Project amount"
                                name="amount" required>
                        </div>
                        <input type="hidden" value="{{ $jobDetails->id }}" name="job_id" required/>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

               
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div> --}}
   
@endsection

@push('scripts')
    <script>
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 2000);
    </script>
@endpush
