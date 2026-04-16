@extends('admin.layouts.app')

@section('content')
@if(in_array(auth()->user()->role, ['admin', 'hr']))
<div class="container-fluid">

    <!-- Header -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <h3 class="text-white">Create New User</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="register-section">
                <div class="card register-card">

                    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Avatar Upload -->
                        <div class="avatar-upload-box">
                            <label for="userprofileImage" class="avatar-label" style="cursor:pointer;">
                                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png"
                                     id="userpreviewImage"
                                     class="avatar-preview">
                                <div class="avatar-overlay">Upload</div>
                            </label>

                            <input type="file" id="userprofileImage" name="profile" accept="image/*" hidden>

                            @error('profile')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Form Grid -->
                        <div class="register-grid">

                            <!-- Username -->
                            <div>
                                <input type="text" name="username"
                                       value="{{ old('username') }}"
                                       class="field-control @error('username') is-invalid @enderror"
                                       placeholder="Username">
                                @error('username')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <input type="email" name="email"
                                       value="{{ old('email') }}"
                                       class="field-control @error('email') is-invalid @enderror"
                                       placeholder="Email Address">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <input type="text" name="phone"
                                       value="{{ old('phone') }}"
                                       class="field-control @error('phone') is-invalid @enderror"
                                       placeholder="Phone Number">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div>
                                <select name="role"
                                        class="field-control @error('role') is-invalid @enderror">
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                                    <option value="hr" {{ old('role')=='hr'?'selected':'' }}>HR</option>
                                    <option value="team_member" {{ old('role')=='team_member'?'selected':'' }}>Team Member</option>
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <input type="password" name="password"
                                       class="field-control @error('password') is-invalid @enderror"
                                       placeholder="Password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <input type="password" name="password_confirmation"
                                       class="field-control"
                                       placeholder="Confirm Password">
                            </div>

                            <!-- Gender -->
                            <div>
                                <select name="gender"
                                        class="field-control @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                                </select>
                                @error('gender')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Education -->
                            <div>
                                <input type="text" name="education"
                                       value="{{ old('education') }}"
                                       class="field-control @error('education') is-invalid @enderror"
                                       placeholder="Education">
                                @error('education')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="full-width">
                                <input type="text" name="address"
                                       value="{{ old('address') }}"
                                       class="field-control @error('address') is-invalid @enderror"
                                       placeholder="Address">
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="register-btn-wrap">
                                <button type="submit" class="register-btn">
                                    Create User
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@else
<div class="container-fluid">
    <div class="text-center mt-5">
        <h4 style="color:red;">Access Denied</h4>
        <p>You are not authorized to view this page.</p>
    </div>
</div>
@endif
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('userprofileImage');
    const preview = document.getElementById('userpreviewImage');

    if (input && preview) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert('Please select a valid image');
                    return;
                }

                preview.src = URL.createObjectURL(file);
            }
        });
    }

});
</script>
@endsection