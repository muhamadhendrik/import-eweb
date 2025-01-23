@extends('layouts.back.app')
@section('title', $title)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">Change Password</h5>
            <div class="card-body">
                <form id="myForm" class="row g-6" method="POST" action="{{ route('users.change-password-update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" placeholder="Enter your current password" name="current_password"/>
                            @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">New Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" placeholder="Enter your new password" name="password"/>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div>
                            <label for="current_password" class="form-label">Confirmation Password</label>
                            <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" id="current_password" placeholder="Enter your confirmation password" name="password_confirmation"/>
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('dashboard') }}'">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@stop
