@extends('admin.layouts.app')

@section('content')
@if(in_array(auth()->user()->role, ['admin', 'hr']))
<div class="container-fluid">

    <!-- Header -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <h3 class="text-white">Create New Client</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="register-section">
                <div class="card register-card">

                    <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Avatar Upload -->
                        <div class="avatar-upload-box">
                            <label for="clientprofileImage" class="avatar-label">
                                <img src="{{ old('profile') 
                                        ? asset('uploads/users/'.old('profile')) 
                                        : 'https://cdn-icons-png.flaticon.com/512/847/847969.png' }}"
                                    id="clientpreviewImage"
                                    class="avatar-preview">
                                <div class="avatar-overlay">Upload</div>
                            </label>
                            <input type="file" id="clientprofileImage" name="profile" accept="image/*" hidden>
                            @error('profile')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                        </div>

                        <!-- Form Grid -->
                        <div class="register-grid">

                            <!-- Company Name -->
                            <div>
                                <input type="text" name="company_name"  value="{{ old('company_name') }}"  class="field-control"  placeholder="Company Name">
                                @error('company_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Founder Name -->
                            <div>
                                <input type="text" name="founder_name"  value="{{ old('founder_name') }}"  class="field-control" placeholder="Founder Name">
                                @error('founder_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <input type="email" name="email" value="{{ old('email') }}" class="field-control @error('email') is-invalid @enderror" placeholder="Email Address">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <input type="text" name="phone"  value="{{ old('phone') }}" class="field-control" placeholder="Phone Number">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>  
                            <!-- Website -->
                            <div>
                                <input type="text" name="website_link"  value="{{ old('website_link') }}" class="field-control" placeholder="Website Link">
                                @error('website_link')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Company Address -->
                            <div class="full-width">
                                <input type="text" name="company_address" value="{{ old('company_address') }}"  class="field-control" placeholder="Company Address">
                                @error('company_address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                             <!-- Company Sector -->
                            <div>
                                <select name="company_sector" id="sectorSelect"
                                    class="field-control @error('company_sector') is-invalid @enderror">

                                    <option value="">Select Company Sector</option>

                                    <option value="IT" {{ old('company_sector')=='IT' ? 'selected' : '' }}>IT</option>
                                    <option value="Finance" {{ old('company_sector')=='Finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="Healthcare" {{ old('company_sector')=='Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                    <option value="Education" {{ old('company_sector')=='Education' ? 'selected' : '' }}>Education</option>
                                    <option value="Real Estate" {{ old('company_sector')=='Real Estate' ? 'selected' : '' }}>Real Estate</option>
                                    <option value="Manufacturing" {{ old('company_sector')=='Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                    <option value="E-commerce" {{ old('company_sector')=='E-commerce' ? 'selected' : '' }}>E-commerce</option>
                                    <option value="Other" {{ old('company_sector')=='Other' ? 'selected' : '' }}>Other</option>
                                </select>

                                {{-- Validation message --}}
                                @error('company_sector')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                           
                             <!-- Password -->
                            <div>
                                <input type="password" name="password" class="field-control @error('password') is-invalid @enderror" placeholder="Password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Confirm Password -->
                            <div>
                                <input type="password" name="password_confirmation"  class="field-control" placeholder="Confirm Password">
                            </div>
                            <div id="otherSectorDiv" class="full-width" style="display: none;">
                                <input type="text" name="company_sector_other" value="{{ old('company_sector_other') }}" class="field-control" placeholder="Enter Company Sector">
                                @error('company_sector_other')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Hidden Role -->
                            <input type="hidden" name="role" value="client">
                            <!-- Submit -->
                            <div class="register-btn-wrap">
                                <button type="submit" class="register-btn">
                                    Create Client
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('clientprofileImage');
    const preview = document.getElementById('clientpreviewImage');

    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // validate image
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
@section('scripts')
<script>
const sectorSelect = document.getElementById('sectorSelect');
const otherDiv = document.getElementById('otherSectorDiv');

function toggleOtherField() {
    if (sectorSelect.value === 'Other') {
        otherDiv.style.display = 'block';
    } else {
        otherDiv.style.display = 'none';
    }
}

// Run on change
sectorSelect.addEventListener('change', toggleOtherField);

// Run on page load (for old value)
window.addEventListener('load', toggleOtherField);
</script>
@endsection
@else
<div class="container-fluid">
    <div class="text-center mt-5">
        <h4 style="color:red;">Access Denied</h4>
        <p>You are not authorized to view this page.</p>
    </div>
</div>
@endif
@endsection


