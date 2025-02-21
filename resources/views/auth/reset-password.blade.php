@extends('layouts.auth')

@section('content')
	<div class="login-box">
      <div class="login-logo">
       <a href="{{ route('login') }}"><b>Charted</b>Accountant</a>
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Setup New Password For Your Account</p>
          <form method="POST" action="{{ route('password.store') }}">
			@csrf
			<input type="hidden" name="token" value="{{ $request->route('token') }}">
			<div class="input-group mb-3">
				<input type="email" class="form-control" value="{{ old('email', $request->email) }}" name="email" placeholder="Email">
				<div class="input-group-text">
					<span class="fas fa-envelope"></span>
				</div>
				@if ($errors->has('email'))
					<div id="password-error" class="text-danger">{{ $errors->first('email') }}</div>
				@endif
            </div>
            <div class="input-group mb-3">
				<input type="password" class="form-control" name="password" placeholder="Password">
				<div class="input-group-text">
					<span class="fas fa-lock"></span>
				</div>
				@if ($errors->has('password'))
					<div id="password-error" class="text-danger">{{ $errors->first('password') }}</div>
				@endif
            </div>
            <div class="input-group mb-3">
				<input type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password">
				<div class="input-group-text">
					<span class="fas fa-lock"></span>
				</div>
				@if ($errors->has('password_confirmation'))
					<div id="password-error" class="text-danger">{{ $errors->first('password_confirmation') }}</div>
				@endif
            </div>
            <div class="row">
              <!-- /.col -->
              <div class="col-4">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-block btn-primary">{{ __('Reset Password') }}</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
@stop