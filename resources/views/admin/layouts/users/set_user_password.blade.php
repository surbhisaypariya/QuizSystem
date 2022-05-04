@extends('admin.app')

@section('content')

<div class="col-xl-6">
    <div class="login-form-m">
        <h1>Set Password</h1>
        @include('admin/flash-message')
        <form method="POST" action="{{ route('password_set_user') }}" class="mt-4">
            @csrf
            <div class="form-outline mb-4">
                <label for="email" class="form-label">Email</label>
                
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="{{ trans('translate.placeholder.email') }}">
                
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            
            <div class="form-outline mb-4">
                <label for="password" class="form-label">Password</label>
                
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter Password">
                <p>Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</p>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            
            <div class="form-outline mb-4">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Enter Confirm Password">
                
                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            
            <div class="form-outline mb-4">
                <button type="submit" class="btn btn-primary">Reset Password</button>
                <a href="{{ route('user.index')}}" class="btn outline-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection