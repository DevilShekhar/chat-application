@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
   <style>
      /* AVATAR */
      .avatar-circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#fff;
      font-weight:bold;
      }
      /* EMOJI PICKER POSITION */
      #emoji-container {
      position: absolute;
      bottom: 70px;
      left: 10px;
      z-index: 9999;
      }

.app-chat-msg-chat {
    height: calc(100vh - 50px) !important; /*  adjust this value */
    overflow-y: auto !important;
     scroll-behavior: auto !important;
}
/* ===== CUSTOM SLIM SCROLLBAR ===== */
.app-chat-msg-chat::-webkit-scrollbar {
    width: 6px;  /*  scrollbar thickness */
}

.app-chat-msg-chat::-webkit-scrollbar-track {
    background: transparent; /* optional */
}

.app-chat-msg-chat::-webkit-scrollbar-thumb {
    background: #888;        /* scrollbar color */
    border-radius: 10px;
}

.app-chat-msg-chat::-webkit-scrollbar-thumb:hover {
    background: #555;
}
/* hide edit by default */
.chat-msg-content .edit-btn {
    display: none;
}

/* show on hover */
.chat:hover .edit-btn {
    display: inline;
}
.app-chat-sidebar-user-item a {
    display: block !important;
    width: 100% !important;
}
.app-chat-sidebar-user-item .d-flex {
    width: 100% !important;
    padding: 15px !important;
    align-items: center !important;
    box-sizing: border-box !important;
}
.app-chat-sidebar {
    overflow: hidden !important;
}

.app-chat-sidebar-user {
    height: 100% !important;
  
}
.app-chat-sidebar-user-item {
    width: 100% !important;
    display: block !important;
    border-bottom: 1px solid rgba(255,255,255,0.1) !important;
}

      /* MOBILE RESPONSIVE */
   </style>
   <!-- INPUT -->
                      <style>
                     

                        /* Main input container */
                        .input-group {
                        display: flex;
                        align-items: center;
                        background: #fff !important;
                        border-radius: 30px;
                        padding:0 !important;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        }
                        .form-control {
                            background-color: #fff !important; 
                            color: #000 !important;
                        }

                        /* Remove Bootstrap boxy look */
                        .input-group-text {
                        background: transparent !important;
                        border: none !important;
                        cursor: pointer;
                        }

                        /* Input field */
                        #message {
                        border: none !important;
                        box-shadow: none !important;
                        outline: none !important;
                        flex: 1;
                        padding: 10px;
                        font-size: 14px;
                        }

                        /* Icons */
                        .chat-icon-btn i,
                        .input-group-text i {
                        font-size: 18px;
                        color: #555;
                        transition: 0.2s;
                        }

                        /* Hover effect */
                        .chat-icon-btn:hover i,
                        .input-group-text:hover i {
                        color: #0084ff;
                        }

                        /* Send button highlight */
                        .input-group-text:last-child i {
                        color: #0084ff;
                        }

                        /* Emoji container */
                        #emoji-container {
                        position: absolute;
                        bottom: 60px;
                        left: 15px;
                        background: #fff;
                        border-radius: 10px;
                        padding: 10px;
                        width: 220px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                        }
                        .download-btn {
                            color: #00cfff;
                            font-size: 16px;
                            margin-left: 10px;
                            text-decoration: none;
                        }.$_COOKIE
                        

                        .download-btn:hover {
                            color: #fff;
                        }
                        /* ================= SKELETON LOADER ================= */
.skeleton-msg {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    align-items: center;
}

.skeleton-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #333;
    animation: pulse 1.2s infinite;
}

.skeleton-lines {
    flex: 1;
}

.skeleton-lines .line {
    height: 10px;
    background: #333;
    margin-bottom: 6px;
    border-radius: 5px;
    animation: pulse 1.2s infinite;
}

.skeleton-lines .short {
    width: 60%;
}

@keyframes pulse {
    0% { opacity: 0.4; }
    50% { opacity: 1; }
    100% { opacity: 0.4; }
}

/* ================= UNREAD DIVIDER ================= */
.unread-divider {
    text-align: center;
    margin: 10px 0;
}

.unread-divider span {
    background: #222;
    color: #00bfff;
    padding: 4px 12px;
    font-size: 12px;
    border-radius: 10px;
}
.mention {
    color: #000000;
    font-weight: 600;
  
}

                      </style>
                    
   <div class="row">
      <div class="col-12">
         <div class="card card-statistics">
            <div class="card-body p-0">
               <div class="row no-gutters ">
                  <!-- ================= SIDEBAR ================= -->
                  <div class="col-xl-4 col-xxl-3">
                    <div class="app-chat-sidebar border-right h-100">

                        <!-- SEARCH -->
                        @if(in_array(auth()->user()->role, ['admin','hr']))
                        <div class="app-chat-sidebar-search px-4 pb-4 pt-4 border-bottom">
                            <div class="input-group">
                                <input class="form-control "
                                    placeholder="Search user..."
                                    type="text"
                                    id="user-search"
                                    autocomplete="off">

                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(auth()->user()->role == 'team_member')
                        <div class="app-chat-sidebar-search px-4 pb-4 pt-4 border-bottom">
                            <form method="GET">
                                <div class="input-group">
                                <input class="form-control border-right-0"
                                        placeholder="Search by email..."
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="ti ti-search"></i>
                                    </span>
                                </div>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- USERS -->
                        <div class="app-chat-sidebar-user scrollbar scroll_dark">

                            {{-- USERS --}}
                            @if(in_array(auth()->user()->role, ['admin','hr','team_member','client']))
                                {{-- TEAM MEMBER EMPTY MESSAGE --}}
                                @if(auth()->user()->role == 'team_member' && !$users->count())
                                <div class="p-3 text-muted">Search user by email to start chat</div>
                                @endif

                                @foreach($users as $user)
                                <div id="user-{{ $user->id }}" class="app-chat-sidebar-user-item">
                                <a href="javascript:void(0)"
                                    onclick="openChat({{ $user->id }}, '{{ $user->username }}', {{ $user->getStatus() === 'online' ? 'true' : 'false' }})">

                                    <div class="d-flex {{ isset($user_id) && $user_id == $user->id ? 'active' : '' }}">
                                        <div class="bg-img">
                                            <div class="avatar-circle"
                                                style="background: {{ $user->bg_colors }}">
                                            {{ strtoupper(substr($user->username,0,2)) }}
                                            </div>
                                            {{-- STATUS ICON --}}
                                            @if($user->getStatus() === 'online')
                                            <i class="bg-img-status bg-success"></i>
                                            @elseif($user->getStatus() === 'away')
                                            <i class="bg-img-status bg-warning"></i>
                                            @else
                                            <i class="bg-img-status bg-danger"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="mb-0 text-white">{{ $user->username }}</h5>
                                        </div>
                                        <div class="ml-auto text-right">
                                            <span id="unread-{{ $user->id }}"
                                                class="badge badge-success"
                                                style="{{ $user->unread_count ? '' : 'display:none;' }}">
                                            {{ $user->unread_count }}
                                            </span>
                                        </div>

                                    </div>
                                </a>
                                </div>
                                @endforeach

                            @endif


                            {{-- GROUPS --}}
                            @if(in_array(auth()->user()->role, ['admin','hr','team_member','client']))

                                @foreach($groups as $group)
                                <div id="group-{{ $group->id }}" class="app-chat-sidebar-user-item">
                                <a href="javascript:void(0)"
                                    onclick="openGroup({{ $group->id }}, '{{ $group->group_name }}', '{{ $group->title }}')">

                                    <div class="d-flex {{ isset($group_id) && $group_id == $group->id ? 'active' : '' }}">
                                        <div class="bg-img">

                                            <div class="avatar-circle bg-success">
                                             <img src="{{ $group->group_profile ? asset('uploads/groups/'.$group->group_profile)  : asset('assets/img/default-group.png') }}" style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
                                            </div>

                                        </div>

                                        <div>
                                            <h5 class="mb-0 text-white">{{ $group->group_name }}</h5>
                                            <p>{{ $group->title }}</p>
                                        </div>

                                        <div class="ml-auto text-right">
                                            <span id="group-unread-{{ $group->id }}"
                                                class="badge badge-success"
                                                style="{{ $group->unread_count ? '' : 'display:none;' }}">
                                            {{ $group->unread_count }}
                                            </span>
                                        </div>

                                    </div>
                                </a>
                                </div>
                                @endforeach

                            @endif

                        </div>
                    </div>
                    </div>
                  <!-- ================= CHAT AREA ================= -->
                  <div class="col-xl-8 col-xxl-9">
                     <!-- HEADER -->
                     <div class="chat-header d-flex align-items-center justify-content-between p-3 px-4 border-bottom ">
                        <div>
                           <h4 id="chat-title" class="mb-0 text-white">
                              @if(isset($type) && $type=='private')
                              {{ $chatUser->username }}
                              @elseif(isset($type) && $type=='group')
                              {{ $group->group_name }}
                              @else
                              Select Chat
                              @endif
                           </h4>
                           <p id="chat-subtitle" class="mb-0 text-muted">
                                @if(isset($type) && $type=='group')
                                    {{ $group->title }}
                                @elseif(isset($type) && $type=='private')
                                    {{ $chatUser->is_online ? 'Online' : 'Offline' }}
                                @endif
                            </p>
                        </div>
                       
                     </div>
                     <!-- CHAT BOX -->
                     <div class=" app-chat-msg-chat p-4">
                        <div id="chat-box">
                           @if(isset($messages))
                           @php $lastDate = null; @endphp
                           @foreach($messages as $msg)
                           @php
                           $msgDate = \Carbon\Carbon::parse($msg->created_at)->format('Y-m-d');
                           @endphp
                           {{-- DATE --}}
                           @if($lastDate != $msgDate)
                           <div class="text-center py-2 text-muted">
                              @php $dateObj = \Carbon\Carbon::parse($msg->created_at); @endphp
                              @if($dateObj->isToday())
                              Today
                              @elseif($dateObj->isYesterday())
                              Yesterday
                              @else
                              {{ $dateObj->format('d M Y') }}
                              @endif
                           </div>
                           @php $lastDate = $msgDate; @endphp
                           @endif
                           {{-- OTHER USER --}}
                            @if($msg->sender_id != auth()->id())
                            <div class="chat" id="msg-{{ $msg->id }}">
                                <div class="chat-img">
                                    <div class="avatar-circle"
                                        style="background: {{ $msg->sender->bg_colors ?? '#6c757d' }}">
                                        {{ strtoupper(substr($msg->sender->username ?? 'U',0,2)) }}
                                    </div>
                                    @if($user->getStatus() === 'online')
                                    <i class="bg-img-status bg-success"></i>
                                    @elseif($user->getStatus() === 'offline')
                                    <i class="bg-img-status bg-warning"></i>
                                    @else
                                    <i class="bg-img-status bg-danger"></i>
                                    @endif
                                </div>
                                <div class="chat-msg">
                                    <div class="chat-msg-content">
                                        <strong style="font-size:12px; color:#a6a9b7;">
                                        {{ $msg->sender->username }}
                                        </strong>
                                        @if($msg->file_path)
                                            @php
                                                $url = url('chat-file/' . basename($msg->file_path));
                                            @endphp
                                            <div style="display:flex;align-items:center;gap:8px;">                                                
                                                <!-- File Icon -->                                        
                                                <!-- File Name -->
                                                <span style="font-size:13px;">
                                                    {{ $msg->file_name }}
                                                </span>
                                                <!-- Download Button -->
                                                <a href="{{ $url }}" download="{{ $msg->file_name }}"
                                                style="color:#007bff;text-decoration:none;">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            @php
                                                $message = e($msg->message);

                                                // URL
                                                $message = preg_replace(
                                                    '/(https?:\/\/[^\s]+)/',
                                                    '<a href="$1" target="_blank" style="color:#007bff;">$1</a>',
                                                    $message
                                                );

                                                // @mention
                                                $message = preg_replace(
                                                    '/@([a-zA-Z0-9_]+)/',
                                                    '<span class="mention">@$1</span>',
                                                    $message
                                                );
                                                @endphp

                                                <p>{!! $message !!}</p>
                                        @endif                                    
                                        <small class="text-muted">
                                            @if($msg->is_edited && $msg->edited_at)
    {{ \Carbon\Carbon::parse($msg->edited_at)->timezone('Asia/Kolkata')->format('h:i A') }}
    <span class="edited-label">(edited)</span>
@else
    {{ $msg->created_at->timezone('Asia/Kolkata')->format('h:i A') }}
@endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endif


                            {{-- MY MESSAGE --}}
                            @if($msg->sender_id == auth()->id())
                            <div class="chat chat-left justify-content-end" id="msg-{{ $msg->id }}">
                            <div class="chat-msg">
                                <div class="chat-msg-content">

                                    <strong style="font-size:12px; color:#000;">
                                        You 

                                        @if(!$msg->file_path && $msg->created_at->diffInMinutes(now()) <= 15)
                                        <span class="edit-btn"
                                            onclick="editMessage({{ $msg->id }})"
                                            style="cursor:pointer; font-size:11px; color:#00bfff; margin-left:5px;">
                                            <i class="dripicons dripicons-pencil"></i> 
                                        </span>
                                        @endif

                                    </strong>

                                    @if($msg->file_path)

                                        @php
                                            $url = url('chat-file/' . basename($msg->file_path));
                                        @endphp

                                        <div style="display:flex;align-items:center;gap:8px;">                                           
                                            <!-- File Icon -->                                     

                                            <!-- File Name -->
                                            <span class="text-white">
                                                {{ $msg->file_name }}
                                            </span>
                                            <!-- Download Button -->
                                            <a href="{{ $url }}" download="{{ $msg->file_name }}"
                                            style="color:#007bff;text-decoration:none;">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </div>
                                    @else
                                      @php
                                                $message = e($msg->message);

                                                // URL
                                                $message = preg_replace(
                                                    '/(https?:\/\/[^\s]+)/',
                                                    '<a href="$1" target="_blank" style="color:#007bff;">$1</a>',
                                                    $message
                                                );

                                                // @mention
                                                $message = preg_replace(
                                                    '/@([a-zA-Z0-9_]+)/',
                                                    '<span class="mention">@$1</span>',
                                                    $message
                                                );
                                                @endphp

                                                <p>{!! $message !!}</p>
                                    @endif

                                   <small class="text-muted">
                                        @if($msg->is_edited && $msg->edited_at)
                                            {{ \Carbon\Carbon::parse($msg->edited_at)->timezone('Asia/Kolkata')->format('h:i A') }}
                                            <span class="edited-label">(edited)</span>
                                        @else
                                            {{ $msg->created_at->timezone('Asia/Kolkata')->format('h:i A') }}
                                        @endif

                                        @if($msg->read_at)
                                            ✔✔
                                        @else
                                            ✔
                                        @endif
                                    </small>
                                </div>
                            </div>
                            </div>
                            @endif
                           @endforeach
                           @endif
                        </div>
                     </div>
                     
                     <div class="app-chat-type">
                        <div class="input-group mb-0">
                           <!-- EMOJI BUTTON -->
                           <div class="input-group-prepend">
                              <button type="button" class="chat-icon-btn input-group-text" id="emoji-btn" title="Emojis">
                              <i class="fa fa-smile-o"></i>
                              </button>
                           </div>
                           <input id="message"
                              class="form-control"
                              placeholder="Type here..."
                              type="text"
                              autocomplete="off">
                            <div class="input-group-prepend">
                                <label class="input-group-text" style="cursor:pointer;">
                                    <i class="fa fa-paperclip"></i>
                                    <input type="file" id="fileInput" multiple hidden>
                                </label>
                            </div>
                           <div class="input-group-prepend">
                              <span class="input-group-text" onclick="sendMessage()">
                              <i class="fa fa-paper-plane"></i>
                              </span>
                           </div>
                        </div>
                       
                        <!-- EMOJI CONTAINER (IMPORTANT POSITION) -->
                        <div id="emoji-container" style="display:none;"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<audio id="sendSound" src="{{ asset('assets/sounds/send.mp3') }}"></audio>
<audio id="receiveSound" src="{{ asset('assets/sounds/receive.mp3') }}"></audio>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // ================= GLOBAL =================
    window.currentChatUser = {{ $user_id ?? 'null' }};
    window.currentGroupId = {{ $group_id ?? 'null' }};
    window.groupIds = @json($groups->pluck('id'));
    window.editingMessageId = null;

    // ================= SOUND =================
    function playSendSound() {
        let a = document.getElementById('sendSound');
        if (a) { a.currentTime = 0; a.play().catch(()=>{}); }
    }

    function playReceiveSound() {
        let a = document.getElementById('receiveSound');
        if (a) { a.currentTime = 0; a.play().catch(()=>{}); }
    }

    // ================= EMOJI =================
    function initEmojiPicker() {
        const button = document.getElementById('emoji-btn');
        const container = document.getElementById('emoji-container');
        const input = document.getElementById('message');

        if (!button || !container || !input) return;

        container.innerHTML = "";

        const picker = new EmojiMart.Picker({
            theme: "dark",
            previewPosition: "none",
            onEmojiSelect: (emoji) => {
                input.value += emoji.native;
                input.focus();
            }
        });

        container.appendChild(picker);

        button.onclick = function (e) {
            e.stopPropagation();
            container.style.display =
                container.style.display === 'block' ? 'none' : 'block';
        };
    }

    // close emoji
    document.addEventListener('click', function(e) {
        const container = document.getElementById('emoji-container');
        const button = document.getElementById('emoji-btn');

        if (!container || !button) return;

        if (!container.contains(e.target) && !button.contains(e.target)) {
            container.style.display = 'none';
        }
    });

    // ================= FILE INPUT =================
    function bindFileInput() {
        let input = document.querySelector('#fileInput');
        if (!input) return;

        input.onchange = function () {
            sendFiles();
        };
    }

    document.addEventListener("DOMContentLoaded", function () {
        initEmojiPicker();
        bindFileInput();
    });

    function saveScrollPosition() {
        let box = document.querySelector('.app-chat-msg-chat');
        if (!box || !window.currentChatUser) return;

        localStorage.setItem('scroll_' + window.currentChatUser, box.scrollTop);
    }

    function restoreScrollPosition() {
        let box = document.querySelector('.app-chat-msg-chat');
        if (!box || !window.currentChatUser) return;

        let pos = localStorage.getItem('scroll_' + window.currentChatUser);

        if (pos) {
            box.scrollTop = pos;
        } else {
            box.scrollTop = box.scrollHeight;
        }
    }
    // ================= OPEN CHAT =================
    function openChat(userId, username, isOnline = true) {

        saveScrollPosition();
        window.unreadInserted = false;
        window.isChatLoading = true;

        // ✅ RESET BADGE UI
        $('#unread-' + userId).text('').hide();

        // header + active
        $('.app-chat-sidebar-user-item .d-flex').removeClass('active');
        $('#user-' + userId + ' .d-flex').addClass('active');

        $('#chat-title').text(username);
        $('#chat-subtitle').text(isOnline ? 'Online' : 'Offline');

        // ✅ MARK AS READ (DB)
        $.post('/chat/mark-as-read', {
            _token: "{{ csrf_token() }}",
            user_id: userId
        });

        // loader
        $('#chat-box').html(`
            ${[1,2,3,4].map(() => `
                <div class="skeleton-msg">
                    <div class="skeleton-avatar"></div>
                    <div class="skeleton-lines">
                        <div class="line"></div>
                        <div class="line short"></div>
                    </div>
                </div>
            `).join('')}
        `);

        $.get('/chat/private/' + userId, function(res) {

            let html = $(res).find('#chat-box').html();
            let box = document.querySelector('.app-chat-msg-chat');

            $('#chat-box').css({ visibility: 'hidden' }).html(html);

            if (box) box.scrollTop = box.scrollHeight;

            setTimeout(() => {
                $('#chat-box').css({ visibility: 'visible' });

                window.currentChatUser = userId;
                window.currentGroupId = null;
                window.isChatLoading = false;

            }, 30);
        });
    }
    function openGroup(groupId, groupName, groupTitle) {

        saveScrollPosition();
        window.unreadInserted = false;
        window.isChatLoading = true;

        // ✅ RESET BADGE
        $('#group-unread-' + groupId).text('').hide();

        $('.app-chat-sidebar-user-item .d-flex').removeClass('active');
        $('#group-' + groupId + ' .d-flex').addClass('active');

        $('#chat-title').text(groupName);
        $('#chat-subtitle').text(groupTitle);

        // ✅ MARK AS READ
        $.post('/chat/group/mark-as-read', {
            _token: "{{ csrf_token() }}",
            group_id: groupId
        });

        $('#chat-box').html(`LOADING...`);

        $.get('/chat/group/' + groupId, function(res) {

            let html = $(res).find('#chat-box').html();
            let box = document.querySelector('.app-chat-msg-chat');

            $('#chat-box').css({ visibility: 'hidden' }).html(html);

            if (box) box.scrollTop = box.scrollHeight;

            setTimeout(() => {
                $('#chat-box').css({ visibility: 'visible' });

                window.currentGroupId = groupId;
                window.currentChatUser = null;
                window.isChatLoading = false;

            }, 30);
        });
    }
    // ================= ECHO =================
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: "hjovy9akjespnitckaxc",
        wsHost: "127.0.0.1",
        wsPort: 8080,
        forceTLS: false,
        disableStats: true,
        cluster: "mt1",
    });


    //  REALTIME FIX START 

    // ================= PRIVATE REALTIME =================
    Echo.channel('chat.{{ auth()->id() }}')
    .listen('.MessageSent', (e) => {

        let msg = e.message;
        let senderId = msg.sender_id;

        //  MOVE USER TO TOP
        if (senderId != {{ auth()->id() }}) {
            moveChatToTop(senderId, 'user');
        }

        let existing = $('#msg-' + msg.id);

        // EDIT UPDATE
        if (existing.length) {

            // SAFE TEXT UPDATE
            let textEl = existing.find('p');
            if (textEl.length) {
                textEl.html(linkify(msg.message));
            }

            let small = existing.find('small');
            small.find('.edited-label').remove();

            //  SHOW ONLY EDITED TIME
            if (msg.is_edited && msg.edited_at) {

                let edited = new Date(msg.edited_at).toLocaleTimeString('en-IN', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true

                });

                small.html(`${edited} <span class="edited-label">(edited)</span>`);

            } else {

                let created = new Date(msg.created_at).toLocaleTimeString('en-IN', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true

                });

                small.html(created);
            }

            return;
        }

        // NEW MESSAGE FLOW
        if (msg.sender_id == {{ auth()->id() }}) return;

        playReceiveSound();

        if (window.currentChatUser != senderId) {
            let badge = $('#unread-' + senderId);
            let count = parseInt(badge.text()) || 0;
            badge.text(count + 1).show();
        }

        if (
            msg.sender_id == window.currentChatUser ||
            msg.receiver_id == window.currentChatUser
        ) {
            appendMessage(msg);
        }
    });
    // ================= GROUP REALTIME =================
    window.groupIds.forEach(function(groupId) {

        Echo.channel('group.' + groupId)
        .listen('.MessageSent', (e) => {

            let msg = e.message;

            //  MOVE GROUP TO TOP
            moveChatToTop(groupId, 'group');

            let existing = $('#msg-' + msg.id);

            // EDIT UPDATE
            if (existing.length) {

                // SAFE TEXT UPDATE
                let textEl = existing.find('p');
                if (textEl.length) {
                    textEl.html(linkify(msg.message));
                }

                let small = existing.find('small');
                small.find('.edited-label').remove();

                //  SHOW ONLY EDITED TIME
                if (msg.is_edited && msg.edited_at) {

                    let edited = new Date(msg.edited_at).toLocaleTimeString('en-IN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });

                    small.html(`${edited} <span class="edited-label">(edited)</span>`);

                } else {

                    let created = new Date(msg.created_at).toLocaleTimeString('en-IN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });

                    small.html(created);
                }

                return;
            }

            if (msg.sender_id == {{ auth()->id() }}) return;

            playReceiveSound();

            if (window.currentGroupId != groupId) {
                let badge = $('#group-unread-' + groupId);
                let count = parseInt(badge.text()) || 0;
                badge.text(count + 1).show();
            }

            if (window.currentGroupId == groupId) {
                appendMessage(msg);
            }
        });

    });

    //  REALTIME FIX END 

    function sendMessage() {

        let message = $('#message').val();
        if (!message.trim()) return;

        showLoader();

        // ================= EDIT MODE =================
        if (window.editingMessageId) {

            $.post("/chat/update-message", {
                _token: "{{ csrf_token() }}",
                message_id: window.editingMessageId,
                message: message
            }, function(res) {

                // ❌ DO NOT appendMessage (it already exists)
                // realtime will update OR you can update manually if needed

                // ✅ RESET EDIT MODE
                window.editingMessageId = null;

                $('#message')
                    .val('')
                    .attr('placeholder', 'Type here...');

                // ✅ RESET ICON
                $('.fa-check')
                    .removeClass('fa-check')
                    .addClass('fa-paper-plane');

                removeLoader();

            }).fail(function () {

                removeLoader();
                showToast("Edit failed");

            });

            return; // ⛔ STOP → don't send new message
        }

        // ================= NORMAL SEND =================
        let url = window.currentChatUser
            ? "/chat/private/send"
            : "/chat/group/send";

        let data = {
            _token: "{{ csrf_token() }}",
            message: message
        };

        if (window.currentChatUser) data.user_id = window.currentChatUser;
        if (window.currentGroupId) data.group_id = window.currentGroupId;

        $.post(url, data, function(res) {

            appendMessage(res);

            $('#message').val('');
            $('#emoji-container').hide();

            playSendSound();

            removeLoader();

        }).fail(function () {

            removeLoader();
            showToast("Message failed");

        });
    }
    function editMessage(id) {

        let msgElement = $('#msg-' + id);

        // GET LATEST TEXT SAFELY
        let currentText = msgElement.find('p').text().trim();

        window.editingMessageId = id;

        $('#message')
            .val(currentText)
            .attr('placeholder', 'Editing message...')
            .focus();

        //  CHANGE ICON
        $('.fa-paper-plane').removeClass('fa-paper-plane').addClass('fa-check');
    }
    function showToast(message) {

        let toast = $(`
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: #ff4d4f;
                color: #fff;
                padding: 12px 20px;
                border-radius: 8px;
                z-index: 9999;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            ">
                ${message}
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    // ================= SEND FILE =================
    function sendFiles() {

        let input = document.querySelector('#fileInput');
        if (!input || !input.files.length) return;

        let formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");

        let hasError = false;

        Array.from(input.files).forEach(file => {

            // 🔥 FILE SIZE CHECK (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showToast("File must be less than 5MB");
                hasError = true;
                return;
            }

            formData.append('files[]', file);
        });

        // ❌ STOP upload if any file is invalid
        if (hasError) {
            input.value = '';
            return;
        }

        if (window.currentChatUser)
            formData.append('user_id', window.currentChatUser);

        if (window.currentGroupId)
            formData.append('group_id', window.currentGroupId);

        let url = window.currentChatUser
            ? "/chat/private/send-file"
            : "/chat/group/send-file";

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(res) {
                res.forEach(msg => appendMessage(msg));
                input.value = '';
            },

            // 🔥 ERROR HANDLER (Laravel validation etc.)
            error: function(err) {

                let message = "Upload failed";

                if (err.responseJSON?.errors) {
                    let firstError = Object.values(err.responseJSON.errors)[0];
                    message = firstError[0];
                }

                showToast(message);
            }
        });
    }

   function linkify(text) {
        if (!text) return '';

        // URL
        let urlPattern = /(https?:\/\/[^\s]+)/g;

        // @username
        let mentionPattern = /@([a-zA-Z0-9_]+)/g;

        text = text.replace(urlPattern, function(url) {
            return `<a href="${url}" target="_blank" style="color:#00bfff;">${url}</a>`;
        });

        text = text.replace(mentionPattern, function(match, username) {
            return `<span class="mention">@${username}</span>`;
        });

        return text;
    }
    // ================= APPEND MESSAGE =================
    function appendMessage(msg) {

        let currentUserId = {{ auth()->id() }};

        let timeText = '';

        if (msg.is_edited && msg.edited_at) {
            let edited = new Date(msg.edited_at).toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            timeText = `${edited} <span class="edited-label">(edited)</span>`;
        } else {
            let created = new Date(msg.created_at).toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            timeText = created;
        }

        let url = msg.file_url 
            || (msg.file_path ? '/chat-file/' + msg.file_path.split('/').pop() : null);

        let html = '';

        // ================= EDIT LOGIC =================
        let isEditable = false;

        if (msg.created_at) {
            let createdTime = new Date(msg.created_at);
            let now = new Date();
            let diffMinutes = (now - createdTime) / 60000;
            isEditable = diffMinutes <= 15;
        }

        let editBtn = (!url && isEditable)
            ? `<span class="edit-btn"
                    onclick="editMessage(${msg.id})"
                    style="cursor:pointer; font-size:11px; color:#00bfff; margin-left:5px;">
                    <i class="dripicons dripicons-pencil"></i>
            </span>`
            : '';

        // ================= READ LOGIC FIX (IMPORTANT) =================
        let isRead = msg.is_read_for_me !== undefined
            ? msg.is_read_for_me
            : msg.read_at; // fallback for old data

        // ================= MY MESSAGE =================
        if (msg.sender_id == currentUserId) {

            let tick = isRead ? '✔✔' : '✔';

            let content = url
                ? `<div class="file-msg">
                        <div class="file-row">
                            <span class="file-name">${msg.file_name}</span>
                            <a href="${url}" download="${msg.file_name}" class="download-btn">
                                <i class="fa fa-download"></i>
                            </a>
                        </div>
                </div>`
                : `<p>${linkify(msg.message)}</p>`;

            html = `
            <div class="chat chat-left justify-content-end" id="msg-${msg.id}">
                <div class="chat-msg">
                    <div class="chat-msg-content">
                        <strong style="color:#000;">
                            You ${editBtn}
                        </strong>
                        ${content}
                        <small class="text-muted">
                            ${timeText} ${tick}
                        </small>
                    </div>
                </div>
            </div>`;
        }

        // ================= OTHER USER =================
        else {

            let content = url
                ? `<div class="file-msg">
                        <div class="file-row">
                            <span class="file-name">${msg.file_name}</span>
                            <a href="${url}" download="${msg.file_name}" class="download-btn">
                                <i class="fa fa-download"></i>
                            </a>
                        </div>
                </div>`
                : `<p>${linkify(msg.message)}</p>`;

            html = `
            <div class="chat" id="msg-${msg.id}">
                <div class="chat-msg">
                    <div class="chat-msg-content">
                        <strong>${msg.sender?.username || 'User'}</strong>
                        ${content}
                        <small>${timeText}</small>
                    </div>
                </div>
            </div>`;
        }

        // ================= UNREAD DIVIDER FIX =================
        if (
            msg.sender_id != currentUserId &&
            !window.unreadInserted &&
            !isRead   // ✅ FIXED
        ) {
            $('#chat-box').append(`
                <div class="unread-divider">
                    <span>New Messages</span>
                </div>
            `);
            window.unreadInserted = true;
        }

        // ================= FADE-IN =================
        let el = $(html).css({
            opacity: 0,
            transform: 'translateY(10px)'
        });

        $('#chat-box').append(el);

        el.animate({ opacity: 1 }, 200);

        el.css({
            transform: 'translateY(0)',
            transition: '0.2s'
        });

        // ================= SMART SCROLL =================
        let box = document.querySelector('.app-chat-msg-chat');
        if (box) {
            let isNearBottom =
                box.scrollHeight - box.scrollTop - box.clientHeight < 120;

            if (isNearBottom) {
                setTimeout(() => {
                    box.scrollTop = box.scrollHeight;
                }, 50);
            }
        }

        // ================= AUTO REMOVE EDIT =================
        if (isEditable) {
            let createdTime = new Date(msg.created_at);
            let now = new Date();
            let remainingTime = (15 * 60 * 1000) - (now - createdTime);

            if (remainingTime > 0) {
                setTimeout(() => {
                    $(`#msg-${msg.id} .edit-btn`).remove();
                }, remainingTime);
            }
        }
    }
    // ================= SCROLL =================
    // ================= SCROLL =================
    function scrollToBottom(force = true) {
        let box = document.querySelector('.app-chat-msg-chat');
        if (!box) return;
        // OPTIONAL: only scroll if near bottom
        if (!force) {
            let isNearBottom =
                box.scrollHeight - box.scrollTop - box.clientHeight < 120;
            if (!isNearBottom) return;
        }
        requestAnimationFrame(() => {
            box.scrollTo({
                top: box.scrollHeight,
                behavior: "smooth"
            });
        });
    }
    // ================= LOADER =================
    function showLoader() {
        // avoid duplicate loader
        if ($('#chat-loader').length) return;
        let loader = `
            <div id="chat-loader" class="text-center text-muted py-2">
                <i class="fa fa-spinner fa-spin"></i> Loading...
            </div>
        `;
        $('#chat-box').append(loader);
        scrollToBottom();
    }
    function removeLoader() {
        $('#chat-loader').remove();
    }
    // ================= ENTER =================
    $(document).on('keydown', '#message', function(e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    function saveChatOrder() {
        let order = [];
        $('.app-chat-sidebar-user-item').each(function () {
            order.push($(this).attr('id'));
        });
        localStorage.setItem('chat_order', JSON.stringify(order));
    }
    function restoreChatOrder() {
        let order = JSON.parse(localStorage.getItem('chat_order'));
        if (!order) return;
        let container = $('.app-chat-sidebar-user');
        order.forEach(id => {
            let el = $('#' + id);
            if (el.length) {
                container.append(el);
            }
        });
    }
    $(document).ready(function () {
        restoreChatOrder();
    });
    function moveChatToTop(id, type = 'user') {
        let selector = type === 'user'
            ? '#user-' + id
            : '#group-' + id;
        let item = $(selector);
        let container = $('.app-chat-sidebar-user');
        if (item.length && container.length) {
            // ✅ FIXED MOVE
            container.prepend(item.detach());
            // ✅ FIX ACTIVE STATE
            $('.app-chat-sidebar-user-item .d-flex').removeClass('active');
            if (type === 'user' && window.currentChatUser == id) {
                $('#user-' + id + ' .d-flex').addClass('active');
            }
            if (type === 'group' && window.currentGroupId == id) {
                $('#group-' + id + ' .d-flex').addClass('active');
            }
            saveChatOrder();
        }
    }
    $('#user-search').on('keyup', function() {
        let search = $(this).val();
        $.ajax({
            url: '/chat/search-users',
            type: 'GET',
            data: { search: search },
            success: function(users) {
                let html = '';
                users.forEach(user => {
                    let initials = user.username.substring(0,2).toUpperCase();
                    html += `
                    <div id="user-${user.id}" class="app-chat-sidebar-user-item">
                        <a href="javascript:void(0)"
                            onclick="openChat(${user.id}, '${user.username}', true)">
                            <div class="d-flex">
                                <div class="bg-img">
                                    <div class="avatar-circle" style="background:${user.bg_colors || '#6c757d'}">
                                        ${initials}
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0 text-white">${user.username}</h5>
                                </div>
                            </div>
                        </a>
                    </div>`;
                });
                $('.app-chat-sidebar-user').html(html);
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const chatBox = document.getElementById('chat-box');
        if (!chatBox) return;
        const observer = new MutationObserver((mutations) => {
            // ✅ DO NOT SCROLL while loading chat
            if (window.isChatLoading) return;
            let box = document.querySelector('.app-chat-msg-chat');
            if (!box) return;
            // ✅ Only scroll if user is near bottom (smart behavior)
            let isNearBottom =
                box.scrollHeight - box.scrollTop - box.clientHeight < 120;
            if (isNearBottom) {
                box.scrollTop = box.scrollHeight; // instant (no animation)
            }
        });
        observer.observe(chatBox, {
            childList: true,
            subtree: true
        });
    });
</script>
@endsection