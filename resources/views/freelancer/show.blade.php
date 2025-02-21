@extends('layouts.admin')

@section('content')
    <!-- Main content -->
    <main class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h2 class="text-2xl font-semibold">Freelancer Profile</h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Freelancer</li>
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
                        <div class="card shadow-lg rounded-lg mb-5">
                            {{-- <div class="card-header flex justify-between items-center bg-gray-100 py-4 px-6">
                                <h3 class="card-title font-medium text-lg">Freelancer Details </h3> <a href="{{ route('freelancer.index') }}"
                                class="btn btn-dark btn-sm">Back</a>
                            </div> --}}
                            <div
                                class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                <h3 class="card-title mb-0">Freelancer Details</h3>
                                <a href="{{ route('freelancer.index') }}" class="btn btn-dark btn-sm">Back To
                                    Freelancers</a>
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
                                            <td class="py-3 px-4">{{ ucfirst($user->name) . ' ' . $user->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Email</th>
                                            <td class="py-3 px-4">{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Phone number</th>
                                            <td class="py-3 px-4">{{ (!empty(@$user->userDetails->phone_number))?@$user->userDetails->phone_number:"--" }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Date of birth</th>
                                            <td class="py-3 px-4">{{ (!empty(@$user->userDetails->date_of_birth))?@$user->userDetails->date_of_birth:"--" }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Type</th>
                                            <td class="py-3 px-4">{{ @$user->role->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Work type</th>
                                            <td class="py-3 px-4">{{ (!empty(@$user->getHowLikeToWork->howLikeTowork->name))?@$user->getHowLikeToWork->howLikeTowork->name:"--" }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Profile Image</th>
                                            <td class="py-3 px-4"><a href="{{ $user->profile_image_path }}"><img
                                                        class="rounded-lg shadow-sm" width="50px" height="54px"
                                                        src="{{ $user->profile_image_path }}"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Headline</th>
                                            <td class="py-3 px-4">{{ (!empty(@$user->userDetails->profile_headline))?@$user->userDetails->profile_headline:"--" }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">About yourself</th>
                                            <td class="py-3 px-4">
                                                @if(!empty(@$user->userDetails->about_yourself))
                                                <div class="about-text-container">
                                                    <span class="about-text">
                                                        {{ Str::limit(@$user->userDetails->about_yourself, 50) }}
                                                    </span>
                                                    @if (strlen(@$user->userDetails->about_yourself) > 50)
                                                        <a href="javascript:void(0);"
                                                            class="read-more-toggle text-blue-600" style="cursor: pointer; color: #007bff;  ">Read More</a>
                                                        <span
                                                            class="full-text hidden" style="display: none; ">{{ @$user->userDetails->about_yourself }}</span>
                                                    @endif
                                                </div>
                                                @else
                                                --
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Status</th>
                                            <td class="py-3 px-4">
                                                @if ($user->user_status == 1)
                                                    <span class="badge bg-success changeYourStatus" style="cursor: pointer;"
                                                        data-url="{{ route('change-status', $user->id) }}"
                                                        title="Click to Inactive user"><i class="fa fa-check-circle"></i> Active</span>
                                                @endif
                                                @if ($user->user_status == 0)
                                                    <span class="badge bg-danger changeYourStatus" style="cursor: pointer;"
                                                        title="Click to active user"
                                                        data-url="{{ route('change-status', $user->id) }}"><i class="fa fa-times-circle"></i> Inactive</span>
                                                @endif
                                            </td>
                                        </tr>


                                        <tr>
                                            <th class="py-3 px-4 font-medium">Country</th>
                                            <td class="py-3 px-4">{{ (!empty(@$user->country->name))?@$user->country->name:"--" }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Skills</th>
                                            <td class="py-3 px-4"> @php
                                                $skills = @$user->skills;
                                                $skills = @$skills->pluck('name')->implode(' , ');
                                            @endphp
                                                {{ !empty($skills) ? @$skills : '--' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 px-4 font-medium">Sub Category</th>
                                            <td class="py-3 px-4"> @php
                                                $subCategory = @$user->subCategory;
                                                $subCategory = @$subCategory->pluck('name')->implode(' , ');
                                            @endphp
                                                {{ !empty($subCategory) ? @$subCategory : '--' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="py-3 px-4 font-medium">Address</th>
                                            <td class="py-3 px-4">
                                                @php
                                                    $addressParts = array_filter([
                                                        @$user->userDetails->street_address,
                                                        @$user->userDetails->state_provience
                                                    ]);
                                                    $cityParts = array_filter([
                                                        @$user->userDetails->city,
                                                        @$user->userDetails->zip_postalcode
                                                    ]);
                                                @endphp
                                            
                                                @if (!empty($addressParts) || !empty($cityParts))
                                                    {{ implode(', ', $addressParts) }} <br>
                                                    {{ implode(', ', $cityParts) }}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            {{-- <td class="py-3 px-4">
                                                {{ @$user->userDetails->street_address }} ,
                                                {{ @$user->userDetails->state_provience }} </br>
                                                {{ @$user->userDetails->city }} ,
                                                {{ @$user->userDetails->zip_postalcode }}
                                            </td> --}}
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

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
            $(document).on('click', '.changeYourStatus', function() {

                Swal.fire({
                    title: "Are you sure you want to change the status?",
                    // text: "You won't be able to revert this!",
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
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 1000);

            $(document).on('click', '.read-more-toggle', function() {
                const $this = $(this);
                const container = $this.closest('.about-text-container');
                const fullText = container.find('.full-text').text();

                if ($this.text() === 'Read More') {
                    container.find('.about-text').text(fullText);
                    $this.text('Read Less');
                } else {
                    const truncatedText = fullText.substring(0, 50) + '...';
                    container.find('.about-text').text(truncatedText);
                    $this.text('Read More');
                }
            });


        });
    </script>
@endpush
