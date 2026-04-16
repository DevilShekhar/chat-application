@extends('admin.layouts.app')

@section('content')
 @if(in_array(auth()->user()->role, ['admin', 'hr']))
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4 class="create-group">Create a Group</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <!-- Group Name -->
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" name="group_name" class="form-control text-white" placeholder="Group Name..." required>
                            @error('group_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control text-white" placeholder="Group Title..." required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Group Profile Image -->
                    <div class="col-lg-4">
                        <div class="form-group">
                        
                            <input type="file" name="group_profile" class="form-control" accept="image/*">
                            @error('group_profile')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>
                <!-- Search -->
                <div class="form-group">
                    <input type="text" id="memberSearch" class="form-control text-white" placeholder="Search members...">
                </div>

                <!-- Selected Users -->
                <div id="selectedUsers" class="mb-3"></div>

                <p class="text-muted mb-2">AVAILABLE USERS</p>

                <!-- Available Users -->
                <div class="member-list">
                    @foreach($users as $user)

                   

                    <div class="member-item d-flex align-items-center justify-content-between" id="user-{{ $user->id }}">

                        <div class="d-flex align-items-center">
                            <div class="bg-type" style="background: {{ $user->bg_colors }}">
                                <span>{{ strtoupper(substr($user->username,0,2)) }}</span>
                            </div>
                            <div class="ml-2">
                                <div class="member-name text-white">{{ $user->username }}</div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-add" data-id="{{ $user->id }}">
                            Add
                        </button>

                    </div>

                    @endforeach
                </div>

                <button type="submit" class="btn btn-success mt-3">Create Group</button>
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

    // ADD USER
    $(document).on('click', '.btn-add', function () {

        let userId = $(this).data('id');
        let userName = $(this).closest('.member-item').find('.member-name').text();
        let avatar = $(this).closest('.member-item').find('.bg-type').prop('outerHTML');

        if (selectedMembers.includes(userId)) return;

        selectedMembers.push(userId);

        // add to selected
        $('#selectedUsers').append(`
            <div class="selected-user d-flex align-items-center justify-content-between" data-id="${userId}">
                <div class="d-flex align-items-center">
                    ${avatar}
                    <div class="ml-2 text-white">${userName}</div>
                </div>
                <button type="button" class="btn-remove" data-id="${userId}">×</button>
                <input type="hidden" name="members[]" value="${userId}" id="member-${userId}">
            </div>
        `);

        // remove from available
        $('#user-' + userId).remove();
    });

    // REMOVE USER
    $(document).on('click', '.btn-remove', function () {

        let userId = $(this).data('id');
        let userName = $(this).closest('.selected-user').find('.ml-2').text();
        let avatar = $(this).closest('.selected-user').find('.bg-type').prop('outerHTML');

        selectedMembers = selectedMembers.filter(id => id != userId);

        // remove from selected
        $(this).closest('.selected-user').remove();
        $('#member-' + userId).remove();

        // add back to available
        $('.member-list').append(`
            <div class="member-item d-flex align-items-center justify-content-between" id="user-${userId}">
                <div class="d-flex align-items-center">
                    ${avatar}
                    <div class="ml-2">
                        <div class="member-name">${userName}</div>
                    </div>
                </div>
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

    // clear list
    $('.member-list').html('');

    // append matched first
    matched.forEach(function (el) {
        $('.member-list').append(el);
        $(el).show();
    });

    // append unmatched after
    unmatched.forEach(function (el) {
        $('.member-list').append(el);
        $(el).hide(); // optional: hide non-matching
    });
});

});
</script>

@endsection