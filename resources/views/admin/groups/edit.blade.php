@extends('admin.layouts.app')

@section('content')
@if(in_array(auth()->user()->role, ['admin', 'hr']))

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4 class="create-group">Edit Group</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('groups.update', $group->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
              
                    <div class="row">
                        <div class="col-lg-4">
                            <!-- Group Name -->
                            <div class="form-group">
                                <label>Group Name</label>
                                <input type="text" name="group_name" value="{{ $group->group_name }}" class="form-control  text-white" required>
                            </div>
                        </div>
                        <!-- Title -->
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" value="{{ $group->title }}" class="form-control text-white" required>
                            </div>
                        </div>

                        <!-- Current Image -->
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Current Profile</label><br>

                                @if($group->group_profile)
                                    <img src="{{ asset('uploads/groups/'.$group->group_profile) }}" width="70" height="70" style="border-radius:50%;">
                                @else
                                    <p class="text-muted">No Image</p>
                                @endif
                            </div>
                        </div>

                        <!-- Upload New Image -->
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Change Profile</label>
                                <input type="file" name="group_profile" class="form-control" accept="image/*">
                            </div>
                        </div>

                    </div>
                <!-- Search -->
                <div class="form-group">
                    <input type="text" id="memberSearch" class="form-control text-white" placeholder="Search members...">
                </div>

                <!-- Selected Users -->
                <div id="selectedUsers" class="mb-3">

                    @foreach($group->members as $member)

                   

                    <div class="selected-user d-flex align-items-center justify-content-between" data-id="{{ $member->id }}">

                        <div class="d-flex align-items-center">
                            <div class="bg-type" style="background: {{ $member->bg_colors ?? '#6c757d' }}">
                                <span>{{ strtoupper(substr($member->username,0,2)) }}</span>
                            </div>
                            <div class="ml-2 text-white">{{ $member->username }}</div>
                           
                        </div>
                        <div class="ml-2 text-white"> Role :  {{ $member->role }}</div>
                        <button type="button" class="btn-remove" data-id="{{ $member->id }}">×</button>

                        <input type="hidden" name="members[]" value="{{ $member->id }}" id="member-{{ $member->id }}">
                    </div>

                    @endforeach

                </div>

                <p class="text-muted mb-2">AVAILABLE USERS</p>

                <!-- Available Users -->
                <div class="member-list">
                    @foreach($users as $user)

                   

                    @if(!$group->members->contains($user->id))
                    <div class="member-item d-flex align-items-center justify-content-between" id="user-{{ $user->id }}">

                        <div class="d-flex align-items-center">
                            <div class="bg-type " style="background: {{ $user->bg_colors }}">
                                <span>{{ strtoupper(substr($user->username,0,2)) }}</span>
                            </div>
                            <div class="ml-2">
                                <div class="member-name text-white">{{ $user->username }}</div>
                            </div>
                        </div>
                        <div class="member-name text-white">Role : {{ $user->role }}</div>
                        <button type="button" class="btn btn-sm btn-add" data-id="{{ $user->id }}">
                            Add
                        </button>

                    </div>
                    @endif

                    @endforeach
                </div>

                <button type="submit" class="btn btn-success mt-3">Update Group</button>
                <a href="{{ route('groups.index') }}" class="btn btn-secondary mt-3">Back</a>

            </form>

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

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    let selectedMembers = [];

    // preload selected
    $('input[name="members[]"]').each(function () {
        selectedMembers.push(parseInt($(this).val()));
    });

    // ADD USER
    $(document).on('click', '.btn-add', function () {

        let parent = $(this).closest('.member-item');

        let userId = $(this).data('id');
        let userName = parent.find('.member-name').first().text();

        // ✅ GET ROLE FROM TEXT
        let userRole = parent.find('.member-name').last().text().replace('Role :', '').trim();

        let avatar = parent.find('.bg-type').prop('outerHTML');

        if (selectedMembers.includes(userId)) return;

        selectedMembers.push(userId);

        $('#selectedUsers').append(`
            <div class="selected-user d-flex align-items-center justify-content-between" data-id="${userId}">
                <div class="d-flex align-items-center">
                    ${avatar}
                    <div class="ml-2 text-white">${userName}</div>
                </div>
                <div class="ml-2 text-white">Role : ${userRole}</div>
                <button type="button" class="btn-remove" data-id="${userId}">×</button>
                <input type="hidden" name="members[]" value="${userId}" id="member-${userId}">
            </div>
        `);

        // remove from available list
        parent.remove();
    });

    // REMOVE USER
    $(document).on('click', '.btn-remove', function () {

        let parent = $(this).closest('.selected-user');

        let userId = $(this).data('id');
        let userName = parent.find('.ml-2').first().text();

        // ✅ GET ROLE AGAIN
        let userRole = parent.find('.ml-2').last().text().replace('Role :', '').trim();

        let avatar = parent.find('.bg-type').prop('outerHTML');

        selectedMembers = selectedMembers.filter(id => id != userId);

        parent.remove();
        $('#member-' + userId).remove();

        // add back to available list (WITH ROLE)
        $('.member-list').append(`
            <div class="member-item d-flex align-items-center justify-content-between" id="user-${userId}">
                <div class="d-flex align-items-center">
                    ${avatar}
                    <div class="ml-2">
                        <div class="member-name text-white">${userName}</div>
                    </div>
                </div>
                <div class="member-name text-white">Role : ${userRole}</div>
                <button type="button" class="btn btn-sm btn-add" data-id="${userId}">
                    Add
                </button>
            </div>
        `);
    });

    // SEARCH
    $('#memberSearch').on('keyup', function () {

        let value = $(this).val().toLowerCase();

        let matched = [];
        let unmatched = [];

        $('.member-item').each(function () {

            let text = $(this).text().toLowerCase();

            if (text.indexOf(value) > -1) {
                matched.push(this);
            } else {
                unmatched.push(this);
            }
        });

        $('.member-list').html('');

        matched.forEach(function (el) {
            $('.member-list').append(el);
            $(el).show();
        });

        unmatched.forEach(function (el) {
            $('.member-list').append(el);
            $(el).hide();
        });

    });

});
</script>

@endsection