@extends('admin.layouts.app')

@section('content')
@if(in_array(auth()->user()->role, ['admin', 'hr']))
<div class="container-fluid">

    <!-- Header -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <h3 class="text-white">Edit Client</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="register-section">
                <div class="card register-card">

                    <form method="POST" action="{{ route('clients.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Upload -->
                        <div class="avatar-upload-box">
                            <label for="clientprofileImage" class="avatar-label" style="cursor:pointer;">
                                <img src="{{ $user->profile 
                                        ? asset('uploads/users/'.$user->profile) 
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
                                <input type="text" name="company_name"
                                       value="{{ old('company_name', $user->company_name) }}"
                                       class="field-control @error('company_name') is-invalid @enderror"
                                       placeholder="Company Name">
                                @error('company_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Founder Name -->
                            <div>
                                <input type="text" name="founder_name"
                                       value="{{ old('founder_name', $user->founder_name) }}"
                                       class="field-control @error('founder_name') is-invalid @enderror"
                                       placeholder="Founder Name">
                                @error('founder_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <input type="email" name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="field-control @error('email') is-invalid @enderror"
                                       placeholder="Email Address">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <input type="text" name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       class="field-control @error('phone') is-invalid @enderror"
                                       placeholder="Phone Number">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div>
                                <input type="text" name="website_link"
                                       value="{{ old('website_link', $user->website_link) }}"
                                       class="field-control @error('website_link') is-invalid @enderror"
                                       placeholder="Website Link">
                                @error('website_link')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Company Address -->
                            <div class="full-width">
                                <input type="text" name="company_address"
                                       value="{{ old('company_address', $user->company_address) }}"
                                       class="field-control @error('company_address') is-invalid @enderror"
                                       placeholder="Company Address">
                                @error('company_address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Company Sector -->
                            <div>
                                @php
                                    $sectors = ['IT','Finance','Healthcare','Education','Real Estate','Manufacturing','E-commerce'];
                                    $currentSector = old('company_sector', $user->company_sector);
                                @endphp

                                <select name="company_sector" id="sectorSelect"
                                    class="field-control @error('company_sector') is-invalid @enderror">

                                    <option value="">Select Company Sector</option>

                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector }}" {{ $currentSector == $sector ? 'selected' : '' }}>
                                            {{ $sector }}
                                        </option>
                                    @endforeach

                                    <option value="Other" {{ !in_array($currentSector, $sectors) ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>

                                @error('company_sector')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Account Status -->
                            <div>
                                <select name="account_status" class="field-control">
                                    <option value="0" {{ $user->account_status==0?'selected':'' }}>Active</option>
                                    <option value="1" {{ $user->account_status==1?'selected':'' }}>Inactive</option>
                                </select>
                            </div>

                            <!-- Other Sector -->
                            <div id="otherSectorDiv" class="full-width" style="display:none;">
                                <input type="text" name="company_sector_other"
                                       value="{{ !in_array($currentSector, $sectors) ? $currentSector : '' }}"
                                       class="field-control @error('company_sector_other') is-invalid @enderror"
                                       placeholder="Enter Company Sector">
                                @error('company_sector_other')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="register-btn-wrap">
                                <button type="submit" class="register-btn">
                                    Update Client
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
    </div>
</div>
@endif
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Image preview
    const input = document.getElementById('clientprofileImage');
    const preview = document.getElementById('clientpreviewImage');

    if (input && preview) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file && file.type.startsWith('image/')) {
                preview.src = URL.createObjectURL(file);
            }
        });
    }

    // Sector toggle
    const sectorSelect = document.getElementById('sectorSelect');
    const otherDiv = document.getElementById('otherSectorDiv');

    function toggleOtherField() {
        if (sectorSelect.value === 'Other') {
            otherDiv.style.display = 'block';
        } else {
            otherDiv.style.display = 'none';
        }
    }

    sectorSelect.addEventListener('change', toggleOtherField);
    window.addEventListener('load', toggleOtherField);

});
</script>
@endsection