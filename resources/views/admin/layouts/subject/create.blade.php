@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="card card-rounded ">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <h3>Add Subject</h3>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('subject.index') }}" class="btn btn-primary me-2">Show Subject</a> 
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('admin/flash-message')
            <form method="POST" action="{{ route('subject.store') }}" class="mt-4">
                @csrf
                <div class="form-cover">
                    <div class="row">
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Name <span>*</span></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter First Name">
                            
                            @error('name')
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