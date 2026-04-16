@extends('admin.layouts.app')

@section('content')
@if(in_array(auth()->user()->role, ['admin', 'hr']))
<style>

/* Group Header */
.group-header {
    padding: 40px 0;
}

.group-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #6c5ce7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    margin: auto;
    color: #fff;
}

/* Member Card */
.member-card {
    background: #1a1a1a;
    border-radius: 15px;
    overflow: hidden;
    color: white;
    transition: 0.3s;
}

.member-card:hover {
    transform: translateY(-5px);
}

/* Gradient Header */
.card-header-gradient {
    height: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Avatar */
.avatar {
    width: 60px;
    height: 60px;
    background: #fff;
    color: #000;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-top:5rem ;
}
.card-body.text-center {
    margin-top: 1rem;
}
/* Status */
.status {
    font-size: 12px;
    margin-bottom: 10px;
}

/* Button */
.btn-outline-light {
    border-radius: 20px;
}
</style>

    <div class="container-fluid">

        <!--  Group Header -->
    <div class="group-header text-center text-white">
        <div class="group-avatar">
            {{ strtoupper(substr($group->group_name,0,2)) }}
        </div>

        <h3 class="mt-3 text-white">{{ $group->group_name }}</h3>

        <p class="text-muted">
            Created by <strong>{{ $group->creator->username ?? 'N/A' }}</strong> <br>
            Created on {{ $group->created_at->format('m/d/Y') }} • {{ $group->members->count() }} Members
        </p>
    </div>

    <!--  Members Directory -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-white">Members Directory</h4>
        <span class="badge badge-pill badge-primary">
            {{ $group->members->count() }} Members
        </span>
    </div>

    <div class="row">

        @foreach($group->members as $member)

        @php
            $gradients = [
                'linear-gradient(135deg,#ff6a6a,#ff9f43)',
                'linear-gradient(135deg,#28c76f,#81fbb8)',
                'linear-gradient(135deg,#f7971e,#ffd200)'
            ];
            $gradient = $gradients[$loop->index % count($gradients)];
        @endphp

        <div class="col-md-3 mb-4">
            <div class="member-card">

                <!-- Gradient Header -->
                <div class="card-header-gradient" style="background: {{ $gradient }}">
                    <div class="avatar text-white" style="background: {{ $member->bg_colors }}">
                        {{ strtoupper(substr($member->username,0,2)) }}
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body text-center">
                    <h5 class="text-white">{{ $member->username }}</h5>
                    <p class="text-white">{{ $member->role }}</p>

                    <!-- Static status (you can make dynamic later) -->
                   <p class="mb-2 text-white">  
                        @if($member->getStatus() === 'online')
                                <span class="badge badge-success">Online</span>

                            @elseif($member->getStatus() === 'away')
                                <span class="badge badge-warning">Offline</span>

                            @else
                                <span class="badge badge-danger">Logout</span>
                            @endif
                    </p>
                  
                </div>

            </div>
        </div>

        @endforeach

    </div>

    <!-- Back Button -->
    <div class="mt-3">
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">← Back</a>
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