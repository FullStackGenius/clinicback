@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="fs-3">Proposal for @if (isset($project) && !empty($project->id))
                                <a href="{{ route('jobs.show', @$project->id) }}"
                                    class="text-decoration-none text-dark"><b>{{ ucfirst($project->title) }}</b></a>
                            @endif job</div>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Job proposals</li>
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
                        <div class="card mb-4">
                            <div
                                class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                <h3 class="card-title mb-0">Job Proposals</h3>
                                <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">Back To Jobs</a>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="card-body">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Freelancer</th>
                                            <th>Cover letter</th>
                                            <th>Amount</th>
                                            <th>Applied On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $hasContract = $proposals->contains(function ($proposal) {
                                                return $proposal->contract;
                                            });
                                        @endphp
                                        @forelse ($proposals as $proposal)
                                            <tr class="align-middle">
                                                <td>{{ ($proposals->currentPage() - 1) * $proposals->perPage() + $loop->iteration }}
                                                </td>
                                                <td><a href="{{ route('client.show', @$proposal->freelancerUser->id) }}"
                                                        class="text-decoration-none">{{ ucfirst(@$proposal->freelancerUser->name) . ' ' . @$proposal->freelancerUser->last_name }}</a>
                                                </td>
                                                <td>
                                                    <div class="description-container">
                                                        <span
                                                            class="short-description">{{ Str::limit(@$proposal->cover_letter, 60) }}</span>
                                                        <span class="full-description"
                                                            style="display:none;">{{ @$proposal->cover_letter }}</span>
                                                        @if (strlen(@$proposal->cover_letter) > 60)
                                                            <a href="javascript:void(0)" class="read-more-btn">Read More</a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-end">{{ @$proposal->bid_amount }}</td>
                                                <td>{{ @$proposal->created_at }}</td>
                                                <td class="text-center">
                                                    @if ($proposal->contract)
                                                        <span data-contractid="{{ $proposal->contract->id }}"
                                                            class="badge bg-success showContractDetail"
                                                            style=" cursor: pointer;">Contract Assigned</span><i
                                                            data-contractid="{{ $proposal->contract->id }}"
                                                            class="fas fa-info-circle showContractDetail"
                                                            style="cursor: pointer;font-size: 20px;"></i>
                                                    @else
                                                        <button
                                                            class="btn btn-primary btn-sm mb-2 @if (!$hasContract) callAssignButton @else disabledAssignButtonPopup  btn-muted @endif"
                                                            @if ($hasContract) style="opacity: 0.5;"  @else data-proposalid="{{ @$proposal->id }}" data-projectid="{{ @$proposal->project_id }}" @endif>
                                                            <i class="fas fa-user-check"></i> Assign Now
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No job proposals available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer clearfix">
                                {!! $proposals->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Assign Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel"><b>Assign Job To Freelancer</b></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('jobs.assign-job') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="project_start_date" class="form-label">Project Start Date:</label>
                            <input type="datetime-local" class="form-control" id="project_start_date"
                                name="project_start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="project_end_date" class="form-label">Project End Date:</label>
                            <input type="datetime-local" class="form-control" id="project_end_date" name="project_end_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="contract_type" class="form-label">Contract Type:</label>
                            <select class="form-control form-select" id="contract_type" name="contract_type" required>
                                <option value="">Select</option>
                                <option value="hourly">Hourly</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_type" class="form-label">Payment Type:</label>
                            <select class="form-control form-select" id="payment_type" name="payment_type" required>
                                <option value="">Select</option>
                                <option value="milestone">Milestone</option>
                                <option value="lump_sum">Lump Sum</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Project Amount:</label>
                            <input type="text" class="form-control" id="amount" name="amount" required>
                        </div>
                        <input type="hidden" name="project_id" id="project_id" required />
                        <input type="hidden" name="proposal_id" id="proposal_id" required />
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--- contract detail modal---->

    <div class="modal fade" id="contractDetailModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel"><b>Contract Details</b></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>Contract Information</b></h5>
                            <hr style="border: 1px solid #007bff; width: 100px;">
                            <p><strong>Project:</strong> <span id="projectTitle"></span></p>
                            <p><strong>Freelancer:</strong> <span id="freelancerId"></span></p>
                            <p><strong>Amount:</strong> <span id="showAmount"></span></p>
                            <p><strong>Status:</strong> <span id="contractStatus"></span></p>
                            <p><strong>Payment Type:</strong> <span id="paymentType"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h5><b>Contract Period</b></h5>
                            <hr style="border: 1px solid #007bff; width: 100px;">
                            <p><strong>Started:</strong> <span id="startedAt"></span></p>
                            <p><strong>Ended:</strong> <span id="endedAt"></span></p>
                        </div>
                    </div>

                    <hr>

                    <!-- Project Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>Project Information</b></h5>
                            <hr style="border: 1px solid #007bff; width: 100px;">
                            <p><strong>Clinet Name:</strong> <span id="showClinetName"></span></p>

                            <p><strong>Status:</strong> <span id="projectStatus"></span></p>

                            <p><strong>Description:</strong> <span id="projectDescription"></span>
                                <a href="javascript:void(0);" id="readMoreLessLink" class="text-primary"
                                    style="display: none;">Read More</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5><b>Budget & Rate</b></h5>
                            <hr style="border: 1px solid #007bff; width: 100px;">
                            <p><strong>Budget Type:</strong> <span id="budgetType"></span></p>
                            <p><strong>Hourly Rate:</strong> <span id="hourlyRate"></span></p>
                            <p><strong>Project Type:</strong> <span id="projectTypeId"></span></p>
                        </div>
                    </div>

                    <hr>

                    <!-- Proposal Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>Proposal Information</b></h5>
                            <hr style="border: 1px solid #007bff; width: 100px;">
                            <p><strong>Bid Amount:</strong> <span id="bidAmount"></span></p>
                            <p><strong>Status:</strong> <span id="proposalStatus"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h5><b>Cover Letter</b></h5>
                            <hr style="border: 1px solid #007bff; width: 100px;">
                            <p id="coverLetter"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.callAssignButton', function() {
                let projectId = $(this).data('projectid');
                let proposalId = $(this).data('proposalid');
                $('#project_id').val(projectId);
                $('#proposal_id').val(proposalId);
                $('#myModal').modal('show');
            });

            $(document).on('click', '.disabledAssignButtonPopup', function() {

                //Swal.fire("This job already adssign to someone");
                Swal.fire({
                    title: 'Job Already Assigned!',
                    text: 'This job has already been assigned to someone else',
                    icon: 'warning',
                    confirmButtonText: 'Ok',
                    showCloseButton: true, // Add a close button in the corner
                    timer: 5000 // Automatically close after 4 seconds
                });


            });


            $(document).on('click', '.showContractDetail', function() {
                // alert('show contract Details')
                // alert( $(this).data('contractid'));
                let contractid = $(this).data('contractid');
                $.ajax({
                    url: "{{ route('jobs.contract-detail-ajax') }}",
                    method: "POST",
                    dataType: "json", // Ensure this is lowercase
                    data: {
                        contract_id: contractid,
                        _token: "{{ csrf_token() }}" // Include the CSRF token
                    },
                    success: function(response) {
                        console.log(response);
                        console.log(response.data.amount);
                        let projectTitle = response.data.project.title;
                        const projectDetailUrl = `{{ route('jobs.show', ':id') }}`.replace(
                            ':id', response.data.project.id);

                        $('#projectTitle')
                            .html(
                                `<a  href="${projectDetailUrl}" class="text-primary" target="_blank">${projectTitle.charAt(0).toUpperCase() + projectTitle.slice(1)}</a>`
                            );
                        let freelancerName = response.data.proposal.freelancer_user
                            .name + " " + response.data.proposal.freelancer_user
                            .last_name

                        const freelancerDetailUrl = `{{ route('freelancer.show', ':id') }}`
                            .replace(
                                ':id', response.data.proposal.freelancer_user
                                .id);
                        $('#freelancerId').html(
                            `<a href="${freelancerDetailUrl}" class="text-primary" target="_blank">${freelancerName.charAt(0).toUpperCase() + freelancerName.slice(1)}</a>`
                        );
                        $('#showAmount').text('$' + response.data.amount);
                        $('#contractStatus').text(response.data.status);
                        $('#paymentType').text(response.data.payment_type);
                        $('#startedAt').text(response.data.started_at);
                        $('#endedAt').text(response.data.ended_at);

                        // // Populate Project Details
                        let clientDetailUrl = `{{ route('freelancer.show', ':id') }}`.replace(
                            ':id', response.data.project.client_user.id);
                        let clinetName = response.data.project.client_user.name + " " + response
                            .data.project.client_user.last_name

                        $('#showClinetName').html(
                            `<a href="${clientDetailUrl}" class="text-primary" target="_blank">${clinetName.charAt(0).toUpperCase() + clinetName.slice(1)}</a>`
                        );
                        $('#projectDescription').text(response.data.project.description);
                        $('#projectStatus').text(response.data.project.project_status_label);
                        $('#budgetType').text(response.data.project.budget_type_label);
                        if (response.data.project.budget_type == 1) {
                            $('#hourlyRate').text(
                                `${'$'+response.data.project.hourly_from} - ${response.data.project.hourly_to}`
                            );
                        } else {
                            $('#hourlyRate').text(`${'$'+response.data.project.fixed_rate}`);
                        }

                        $('#projectTypeId').text(response.data.project.project_type_label);

                        // // Populate Proposal Details
                        $('#bidAmount').text('$' + response.data.proposal.bid_amount);
                        $('#proposalStatus').text(response.data.proposal.status);
                        $('#coverLetter').text(response.data.proposal.cover_letter);

                        // Handle Read More / Read Less for description
                        const fullDescription = response.data.project.description.trim();
                        const maxLength = 80;

                        if (fullDescription.length > maxLength) {
                            const truncatedDescription = fullDescription.substring(0,
                                maxLength) + '...';
                            $('#projectDescription').text(truncatedDescription);

                            $('#readMoreLessLink').show().text('Read More').off('click').on(
                                'click',
                                function() {
                                    if ($(this).text() === 'Read More') {
                                        $('#projectDescription').text(fullDescription);
                                        $(this).text('Read Less');
                                    } else {
                                        $('#projectDescription').text(truncatedDescription);
                                        $(this).text('Read More');
                                    }
                                });
                        } else {
                            $('#projectDescription').text(fullDescription);
                            $('#readMoreLessLink').hide();
                        }


                        $('#contractDetailModal').modal('show');
                        // Handle the response data as needed
                    },
                    error: function(error) {
                        console.log(error);
                        // Handle any error that occurs during the request
                    }
                });


            });

            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 2000);
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
