<div class="col-12">
    <h6>1. Account Details</h6>
    <hr class="mt-0" />
</div>
<div class="col-md-6">
    <label class="form-label" for="name">Full Name</label>
    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', isset($isEdit) && $isEdit ? $user->name : '') }}" />
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="col-md-6">
    <label class="form-label" for="email">Email</label>
    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', isset($isEdit) && $isEdit ? $user->email : '') }}" />
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="col-md-6">
    <label class="form-label" for="password">Password</label>
    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" />
    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="col-md-6">
    <label class="form-label" for="confirm_password">Confirm Password</label>
    <input type="password" id="confirm_password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" />
    @error('password_confirmation')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="col-md-12">
    <label class="form-label" for="role_select2">Role</label>
    <select id="role_select2" name="roles" class="form-select select2 @error('roles') is-invalid @enderror">
        <option value="">Select</option>
        @foreach ($roles as $role)
        <option value="{{ $role->name }}" {{ old('roles', isset($isEdit) && $isEdit && $user->hasRole($role->name) ? 'selected' : '') }}>{{
            $role->name }}</option>
        @endforeach
    </select>
    @error('roles')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
