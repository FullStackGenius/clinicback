@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="fs-3 text-dark">Client Jobs</h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Client Jobs</li>
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
                        <div class="card mb-4 shadow-sm">
                            <div  class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                <h3 class="card-title">Client Jobs</h3>
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
                            <div class="card-body p-3">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Job Title</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Posted By</th>
                                            <th>Posted On</th>
                                            <th>Freelancer Proposal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jobs as $job)
                                            <tr class="align-middle">
                                                <td>
                                                    @php
                                                        $perPage = $jobs->perPage(); 
                                                        $currentPage = $jobs->currentPage(); 
                                                        $startingSerno = ($currentPage - 1) * $perPage; 
                                                    @endphp
                                                    {{ $startingSerno + $loop->iteration }}
                                                </td>
                                                <td>{{ @$job->title }}</td>
                                                <td>
                                                    <div class="description-container">
                                                        <span class="short-description">{{ Str::limit(@$job->description, 60) }}</span>
                                                        <span class="full-description" style="display:none;">{{ @$job->description }}</span>
                                                        @if(strlen(@$job->description) > 60)
                                                            <a href="javascript:void(0)" class="read-more-btn">Read More</a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @switch(@$job->project_status)
                                                        @case(1)
                                                            <span class="badge bg-success">{{ @$job->project_status_label }}</span>
                                                            @break
                                                        @case(2)
                                                            <span class="badge bg-primary">{{ @$job->project_status_label }}</span>
                                                            @break
                                                        @case(3)
                                                        <span class="badge bg-primary">{{ @$job->project_status_label }}</span>
                                                        @break
                                                        @case(4)
                                                            <span class="badge bg-warning">{{ @$job->project_status_label }}</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td><a href="{{ route('client.show', @$job->clientUser->id) }}">{{ ucfirst(@$job->clientUser->name) }} {{ @$job->clientUser->last_name }}</a></td>
                                                <td><p title="{{ @$job->created_at }}">{{ @$job->created_at->diffForHumans() }}</p></td>
                                                <td><a href="{{ route('job-proposal', @$job->id) }}" class="btn btn-primary btn-sm mb-2">View Proposal</a></td>
                                                <td style="display: flex;column-gap: 5px;">
                                                    <a href="{{ route('jobs.show', @$job->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                        Details</a>
                                                </td>
                                            </tr>
                                       

                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No jobs available</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer clearfix">
                                {!! $jobs->links('pagination::bootstrap-4') !!}
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
            $('.read-more-btn').on('click', function() {
                var $this = $(this);
                var $descriptionContainer = $this.closest('.description-container');
                
                // Toggle visibility of descriptions
                $descriptionContainer.find('.short-description').toggle();
                $descriptionContainer.find('.full-description').toggle();
                
                // Change button text
                if ($descriptionContainer.find('.full-description').is(':visible')) {
                    $this.text('Read Less');
                } else {
                    $this.text('Read More');
                }
            });
        });
    </script>
@endpush
