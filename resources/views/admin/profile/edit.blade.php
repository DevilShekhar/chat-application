@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <!-- HEADER -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <h3 class="text-white">Edit Profile</h3>
        </div>
    </div>
     <!-- Success Alert -->
                @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'success',
                            title: "{{ session('success') }}",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
                @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="register-section">
                <div class="card register-card">

                    <!-- FORM START -->
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- AVATAR UPLOAD (SAME AS USER EDIT) -->
                        <div class="avatar-upload-box">
                            <label for="profileImage" class="avatar-label">
                                <img src="{{ $user->profile 
                                    ? asset('uploads/users/'.$user->profile) 
                                    : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }}"
                                     id="previewImage"
                                     class="avatar-preview">
                                <div class="avatar-overlay">Upload</div>
                            </label>
                            <input type="file" id="profileImage" name="profile" hidden>
                        </div>

                        <!-- SAME GRID -->
                        <div class="register-grid">

                            <div>
                                <input type="text"
                                       name="username"
                                       value="{{ old('username', $user->username) }}"
                                       class="field-control"
                                       placeholder="Username">
                            </div>

                            <div>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="field-control"
                                       placeholder="Email">
                            </div>

                            <div>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       class="field-control"
                                       placeholder="Phone">
                            </div>

                            <div>
                                <select name="gender" class="field-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ $user->gender=='male'?'selected':'' }}>Male</option>
                                    <option value="female" {{ $user->gender=='female'?'selected':'' }}>Female</option>
                                </select>
                            </div>

                            <div>
                                <input type="text"
                                       name="education"
                                       value="{{ old('education', $user->education) }}"
                                       class="field-control"
                                       placeholder="Education">
                            </div>

                            <div class="full-width">
                                <input type="text"
                                       name="address"
                                       value="{{ old('address', $user->address) }}"
                                       class="field-control"
                                       placeholder="Address">
                            </div>

                            <!-- SUBMIT -->
                            <div class="register-btn-wrap">
                                <button type="submit" class="register-btn">
                                    Update Profile
                                </button>
                            </div>

                        </div>

                    </form>
                    <!-- FORM END -->

                </div>
            </div>

        </div>
    </div>

</div>
@endsection


@section('scripts')
<script>
document.getElementById('profileImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('previewImage').src = URL.createObjectURL(file);
    }
});
</script>
@endsection