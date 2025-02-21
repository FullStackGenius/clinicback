<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<!-- icheck bootstrap -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- Primary Meta Tags -->
		<title>{{ env("APP_NAME") }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="title" content="">
		<meta name="author" content="">
		<meta name="description" content="">
		<meta name="keywords" content="" />
		<meta name="color-scheme" content="light dark">
		<!-- OPTIONAL LINKS -->
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Bootstrap 5 -->
		<link rel="stylesheet" src="{{ asset('assets/plugins/bootstrap-5.3.3/css/bootstrap.min.css') }}">
		<!-- overlayScrollbars -->
		<link rel="stylesheet" src="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
		<!-- Font Awesome -->
		<!--link rel="stylesheet" src="{{ asset('assets/plugins/fontawesome-6.7.2/fontawesome.min.css') }}"-->
		<!--link src="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}"-->
		<!--link rel="stylesheet" src="{{ asset('assets/plugins/fontawesome-free-5.15.4-web/css/all.min.css') }}"-->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" integrity="sha256-mUZM63G8m73Mcidfrv5E+Y61y7a12O5mW4ezU3bxqW4=" crossorigin="anonymous">
		<!-- REQUIRED LINKS -->
		<!-- Theme style -->
		<link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}">
		<link rel="stylesheet" src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
		<link rel="icon" href="{{ optional(\App\Models\Setting::find(1))->website_logo_path }}" type="image/x-icon">
	</head>
    <body class="login-page">
		@yield('content')
		<script>
			var BASE_URL = "{{ url('/') }}";
		</script>
		<!-- AdminLTE App -->
		<script src="{{ asset('assets/js/adminlte.js') }}"></script>
		<!-- jQuery -->
		<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
		<!-- Bootstrap 5 -->
		<script src="{{ asset('assets/plugins/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
		<!-- overlayScrollbars -->
		<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!-- Jquery Validation plugin -->
		<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
		<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
		<script src="{{ asset('modules-customjs/common/messages.js') }}"></script> 
		<script src="{{ asset('modules-customjs/common/url.js') }}"></script> 
		<!-- Common Helper functions -->
		<script src="{{ asset('modules-customjs/common/helper.js') }}"></script>
		<!-- Page Level JS --> 
		@stack('scripts')
    </body>
</html>
