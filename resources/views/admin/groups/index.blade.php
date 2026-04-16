@extends('admin.layouts.app')
@section('content')
    @if(in_array(auth()->user()->role, ['admin', 'hr']))
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#28a745'
                });
            });
        </script>
        @endif
        <!-- begin container-fluid -->
        <div class="container-fluid">
            <h3 class="mb-4 text-white">All Groups</h3>
            <!-- begin row -->
            <style>
                .table tbody tr{
                    background: transparent;
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
                                            <th class="text-white">Dp</th>
                                            <th class="text-white">Group Name</th>
                                            <th class="text-white">Members</th>
                                            <th class="text-white">Created Date</th>
                                            <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groups as $group)
                                            <tr>
                                                <td class="text-white">{{ $loop->iteration }}</td>
                                                <td> 
                                                    <img src="{{ $group->group_profile ? asset('uploads/groups/'.$group->group_profile)  : asset('assets/img/default-group.png') }}" style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                                                </td>
                                                <td class="text-white">{{ $group->group_name }}</td>                                                      
                                                <td>                                                            
                                                <a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-white text-black bg-white">
                                                        <i class="fe fe-users mr-1"></i> {{ $group->members->count() }} Members
                                                    </a>
                                                </td>
                                                <td class="text-white">{{ $group->created_at }}</td>
                                                <td>   
                                                    <a   href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-icon btn-outline-primary mr-1"><i class="fe fe-edit"></i></a>
                                                    <!-- <a class="btn btn-sm btn-icon btn-outline-danger text-white"><i class="fe fe-trash"></i></a>                            -->
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
            <!-- end row -->
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
                // CUSTOM SEARCH BOX
                $('#groupSearchInput').on('keyup', function () {
                    table.search(this.value).draw();
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