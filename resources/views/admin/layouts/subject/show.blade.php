@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="card card-rounded ">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <h3>User Detail</h3>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('user.index') }}" class="btn btn-primary me-2">Show User</a> 
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('admin/flash-message')
            <div class="box-inner">
                <div class="row">
                    <div class="col-xl-6 col-md-12 mb-4">
                        <label class="col-sm-3 col-form-label" style="font-weight: 600;">First Name</label>
                        <div class="form-value">{{ $user->first_name }}</div>
                    </div>
                    <div class="col-xl-6 col-md-12 mb-4">
                        <label class="col-sm-3 col-form-label" style="font-weight: 600;">Last Name</label>
                        <div class="form-value">{{ $user->last_name }}</div>
                    </div>
                    <div class="col-xl-6 col-md-12 mb-4">
                        <label class="col-sm-3 col-form-label" style="font-weight: 600;">Email</label>
                        <div class="form-value">{{ $user->email }}</div>
                    </div>
                    <div class="col-xl-6 col-md-12 mb-4">
                        <label class="col-sm-3 col-form-label" style="font-weight: 600;">Username</label>
                        <div class="form-value">{{ $user->username }}</div>
                    </div>
                    
                    <?php 
                    if($user->role == "super_admin"){
                        $role = "Super Admin";
                    }elseif($user->role == "admin"){
                        $role = "Admin";
                    }elseif($user->role == "student"){
                        $role = "Student";
                    }
                    ?>
                    <div class="col-xl-6 col-md-12 mb-4">
                        <label class="col-sm-3 col-form-label" style="font-weight: 600;">Role</label>
                        <div class="form-value">{{ $role }}</div>
                    </div>
                    
                    <div class="col-xl-6 col-md-12 mb-4">
                        <label class="col-sm-3 col-form-label" style="font-weight: 600;">Status</label>
                        <div class="form-value">{{ $user->status ==1? "Active":"Disable" }}</div>
                    </div>
                    @if(Auth::user()->id != $user->id)
                    <div class="col-12">
                        <a href="{{ route('user.edit',$user->id) }}" class="btn btn-primary me-2 ">Edit</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection