@extends('layouts.auth')

@section('content')
	<div class="login-box">
      <div class="login-logo">
        <a href="{{ route('login') }}"><b>Charted</b>Accountant</a>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
			<p class="login-box-msg"> {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
			<form method="POST" action="{{ route('password.email') }}">
				@csrf
				<div class="input-group mb-3">
					<input type="email" name="email" class="form-control" placeholder="Email">
					<div class="input-group-text">
						<span class="fas fa-envelope"></span>
					</div>
					@if ($errors->has('email'))
						<div id="password-error" class="text-danger">{{ $errors->first('email') }}</div>
					@endif
				</div>
				<div class="row">
					<div class="col-4">
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-block btn-primary">Submit</button>
						</div>
					</div>
				</div>
          </form>
        </div>
      </div>
    </div>
@stop