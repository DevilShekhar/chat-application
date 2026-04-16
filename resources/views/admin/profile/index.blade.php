@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2 class="mb-4 text-white">My Profile</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4 align-items-center">
                    <!-- Profile Image -->
                    <div class="col-md-4 text-center">
                        <div class="avatar avatar-xl position-relative">
                           
                            <img src="{{ asset('uploads/users/'.$user->profile) }}" width="120" class="rounded-circle shadow"  alt="Profile Image">
                        </div>
                        <h4 class="mt-3 text-white">{{ $user->username }}</h4>
                        <p class="text-white">{{ $user->email }}</p>
                    </div>

                    <!-- Profile Info -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <strong class="text-white">Mobile No:</strong>
                                <p class="text-white">{{ $user->phone ?? '-' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <strong class="text-white">Gender:</strong>
                                <p class="text-white">{{ ucfirst($user->gender) ?? '-' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <strong class="text-white">Address:</strong>
                                <p class="text-white">{{ $user->address ?? '-' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <strong class="text-white">Education:</strong>
                                <p class="text-white"> {{ $user->education ?? '-' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-pencil"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
