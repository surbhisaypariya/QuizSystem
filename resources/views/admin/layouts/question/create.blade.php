@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="card card-rounded ">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <h3>Add Question</h3>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('question.index') }}" class="btn btn-primary me-2">Show Question</a> 
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('admin/flash-message')
            <form method="POST" action="{{ route('question.store') }}" class="mt-4">
                @csrf
                <div class="form-cover">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 mb-4">
                            <label>Question <span>*</span></label>
                            <input id="question" type="text" class="form-control @error('question') is-invalid @enderror" name="question" value="{{ old('question') }}" required autocomplete="question" autofocus placeholder="Enter question"> 
                            
                            @error('question')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Option 1 <span>*</span></label>
                            <input id="option_1" type="text" class="form-control @error('option_1') is-invalid @enderror" name="option_1" value="{{ old('option_1') }}" required autocomplete="option_1" autofocus placeholder="Enter option one"> 
                            
                            @error('option_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Option 2 <span>*</span></label>
                            <input id="option_2" type="text" class="form-control @error('option_2') is-invalid @enderror" name="option_2" value="{{ old('option_2') }}" required autocomplete="option_2" autofocus placeholder="Enter option two"> 
                            
                            @error('question')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Option 3 <span>*</span></label>
                            <input id="option_3" type="text" class="form-control @error('option_3') is-invalid @enderror" name="option_3" value="{{ old('option_3') }}" required autocomplete="option_3" autofocus placeholder="Enter option three"> 
                            
                            @error('option_3')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-md-12 mb-4">
                            <label>Option 4 <span>*</span></label>
                            <input id="option_4" type="text" class="form-control @error('option_4') is-invalid @enderror" name="option_4" value="{{ old('option_4') }}" required autocomplete="option_4" autofocus placeholder="Enter option four"> 
                            
                            @error('option_4')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-6">
                            <label>Subject <span>*</span></label>
                            <select name="subject" class="form-control" required>
                                <option value="">-- Please Select --</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id}}">{{ $subject->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Answer <span>*</span></label>
                            <input id="answer" type="text" class="form-control @error('answer') is-invalid @enderror" name="answer" value="{{ old('answer') }}" required autocomplete="answer" autofocus placeholder="Enter answer"> 
                            
                            @error('answer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4">
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