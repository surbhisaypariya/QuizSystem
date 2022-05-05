@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="card card-rounded ">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <h3>Add User</h3>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('user.index') }}" class="btn btn-primary me-2">Show User</a> 
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('admin/flash-message')
            <form method="POST" action="{{ route('user.store') }}" class="mt-4">
                @csrf
                <div class="form-cover">
                    <div class="row">
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>First Name <span>*</span></label>
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus placeholder="Enter First Name">
                            
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            
                        </div>
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Last Name <span>*</span></label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus placeholder="Enter Last Name">
                            
                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Email<span>*</span></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email">
                            
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>User Name<span>*</span></label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Enter User Name">
                            
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Role <span>*</span></label>
                            
                            @if($user_role == "super_admin")
                            <select id="role" name="role" class="form-control">
                                <option value="super_admin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="student">Student</option>
                            </select>
                            
                            @elseif($user_role == "admin")
                             <select id="role" name="role" class="form-control">
                                <option value="admin">Admin</option>
                                <option value="student">Student</option>
                            </select>
                            
                            @elseif($user_role == "student")
                            <select id="role" name="role" class="form-control">
                                <option value="student">Student</option>
                            </select>
                            @else
                            <select id="role" name="role" class="form-control">
                                <option value="super_admin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="student">Student</option>
                            </select>
                            @endif
                            
                            @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>                 
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="custom-control custom-switch custom-switch-lg">
                                <input type="checkbox" checked class="form-check-input" id="switch2" name="status">
                                <label class="custom-control-label" for="switch2">Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex align-items-center">
                            <button class="btn btn-primary me-2" type="submit">Save</button>
                            <a href="{{ route('user.index') }}" class="btn btn-light">Cancel</a>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection