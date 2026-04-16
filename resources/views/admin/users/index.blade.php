@extends('admin.layouts.app')
    @section('content')
        @if(in_array(auth()->user()->role, ['admin', 'hr']))
            <div class="container-fluid">
                <h3 class="mb-4 text-white">Users List</h3>
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
                <style>
                    .table > tbody > tr > td {
                    vertical-align: middle;
                    white-space: nowrap;
                    border-top: 1px solid #dee2e6 !important;
                    color:#fff;
                    }
                    .table tbody tr {
                    background: transparent;
                    box-shadow: 0 8px 24px rgba(31, 37, 64, 0.05);
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                    color:#fff;
                    }
                </style>
                <div class="row editable-wrapper">
                    <div class="col-lg-12 ">
                        <div class="card card-statistics" style="min-height: auto;">
                            <div class="card-body p-0">
                            <!-- Added by Samadhan: Table Scroll Wrapper removed -->
                            <div class="table-responsive">
                                <table id="dataTable" class="table  align-middle text-center custom-table mb-0">
                                    <thead>
                                        <tr>
                                        <th class="text-white">ID</th>
                                        <th class="text-white">Profile</th>
                                        <th class="text-white">Name</th>
                                        <th class="text-white">Contact</th>
                                        <th class="text-white"> Role</th>
                                        <th class="text-white">Login Status</th>
                                        <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                           <td>
                                                <img src="{{ $user->profile 
                                                    ? asset('uploads/users/'.$user->profile)
                                                    : asset('assets/img/default-user.png') }}"
                                                    class="avatar-preview"
                                                    style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
                                            </td>
                                            <td>{{ $user->username }}</td>
                                            <td class="text-left">
                                                <p class="text-white">
                                                    <i class="ion ion-ios-mail"></i> {{ $user->email }}
                                                </p>
                                                <p class="text-white">
                                                    <i class="ion ion-ios-call"></i> {{ $user->phone }}
                                                </p>
                                            </td>
                                            
                                            <td>{{ ucfirst($user->role) }}</td>
                                           
                                            <!-- Login Status -->
                                            <td>
                                                @if($user->getStatus() === 'online')
                                                    <span class="badge badge-success">Online</span>
                                                    @elseif($user->getStatus() === 'away')
                                                <span class="badge badge-warning">Offline</span>
                                                    @else
                                                <span class="badge badge-danger">Logout</span>
                                                    @endif
                                            </td>
                                            <!-- Actions -->
                                            <td class="text-nowrap">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                <i class="fe fe-edit"></i>
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}"
                                                    method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger ml-2"
                                                        onclick="confirmDelete(this)">
                                                    <i class="fe fe-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                               
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <script src="{{ asset('assets/js/jquery-3.7.0.min.js') }}"></script>
            <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {

    // INIT DATATABLE
    let table = $('#dataTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: false
    });


});
</script>
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
   function confirmDelete(btn) {
   
       let form = btn.closest('form');
   
       Swal.fire({
           title: 'Are you sure?',
           text: "User will be deactivated!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonText: 'Yes, deactivate!',
           cancelButtonText: 'Cancel'
       }).then((result) => {
           if (result.isConfirmed) {
               form.submit();
           }
       });
   }
</script>
@endsection