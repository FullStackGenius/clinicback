@extends('layouts.auth')

@section('content')
	<div class="login-box">
		<div class="login-logo">
			 <a href="{{ route('login') }}"><b>Charted</b>Accountant</a>
		</div>
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">Sign in to start your session</p>
				<form method="post" action="{{ route('login') }}" id="loginForm">
					@csrf
					<div class="input-group mb-3">
						<input type="email" class="form-control" placeholder="Email" name="email" required>
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
						@if ($errors->has('email'))
							<div id="email-error" class="text-danger">{{ $errors->first('email') }}</div>
						@endif
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control" placeholder="Password" name="password" required>
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
						@if ($errors->has('password'))
							<div id="password-error" class="text-danger">{{ $errors->first('password') }}</div>
						@endif
					</div>
					<div class="row">
						<div class="col-12">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="remember" id="remember_me">
								<label class="form-check-label" for="flexCheckDefault">
									Remember Me
								</label>
							</div>
						</div>
						<div class="col-12 mt-4">
							<div class="d-grid gap-2">
								<button type="submit" class="btn btn-block btn-primary" id="submitButton">Sign In</button>
							  <!--button type="submit" class="btn btn-primary">Sign In</button-->
							</div>
						</div>
					</div>
				</form>
				@if (Route::has('password.request'))
					<p class="mb-1 mt-4">
						<a href="{{ route('password.request') }}">I forgot my password</a>
					</p>
				@endif
				
			</div>
        </div>
    </div>
@stop
