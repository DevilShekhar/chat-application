@extends('admin.layouts.app')

@section('content')
<div class="page-content">
    <div class="page-content-wrapper border">
        <!-- Title -->
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-2 mb-sm-0">Inactive Users</h1>
            </div>
        </div>

        <!-- Card START -->
        <div class="card bg-transparent">
            <!-- Card header START -->
            <div class="card-header bg-transparent border-bottom px-0">
                <div class="row g-3 align-items-center justify-content-between">
                    <!-- Search bar -->
                    <div class="col-md-8">
                        <form class="rounded position-relative" method="GET" action="">
                            <input class="form-control bg-transparent" type="search" name="search" placeholder="Search users..." value="{{ request('search') }}">
                            <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                                <i class="fas fa-search fs-6"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Card header END -->

            <!-- Card body START -->
            <div class="card-body px-0">
                <!-- Table START -->
                <div class="table-responsive border-0">
                    <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="border-0">Profile</th>
                                <th scope="col" class="border-0">Email</th>
                                <th scope="col" class="border-0">Mobile No</th>
                                <th scope="col" class="border-0">Role</th>
                                <th scope="col" class="border-0">Status</th>
                                <th scope="col" class="border-0 rounded-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center position-relative">
                                        <div class="avatar avatar-md">
                                            @if ($user->profile)
                                                <img src="{{ asset('storage/' . $user->profile) }}" class="rounded-circle" alt="">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </div>
                                        <div class="mb-0 ms-2">
                                            <h6 class="mb-0"><a href="#" class="stretched-link">{{ $user->name }}</a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center text-sm-start">{{ $user->email }}</td>
                                <td>{{ $user->mobile_no }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>Inactive</td>
                                <td>
                                    <form action="{{ route('users.activate', $user->id) }}" method="POST" class="d-inline-block activate-form">
                                        @csrf
                                        <button type="submit" class="btn btn-success-soft btn-round mb-0" data-bs-toggle="tooltip" title="Activate">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No inactive users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Card body END -->

            <!-- Card footer START -->
            <div class="card-footer bg-transparent p-0">
                <div class="d-sm-flex justify-content-sm-between align-items-sm-center px-2">
                    <p class="mb-0 text-center text-sm-start">
                        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->total() > 0)
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                        @endif
                    </p>
                    <div>
                        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $users->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            </div>
            <!-- Card footer END -->
        </div>
        <!-- Card END -->
    </div>
</div>

<!-- SweetAlert script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.activate-form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This user will be activated!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, activate!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
        @endif
    });
</script>
@endsection
