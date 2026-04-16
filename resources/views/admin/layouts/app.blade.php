<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Designer Portal') }}</title>
      <!-- Favicons -->
      <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">
      <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
      <!-- Fonts -->
       <meta name="user-id" content="{{ auth()->id() }}">
      <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
      <!-- Vendor CSS -->
      <link href="{{ asset('assets/css/vendors.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/emoji-mart.css') }}" rel="stylesheet">
   </head>
   <body>
      <div class="app">
         <div class="app-wrap">
            <!-- ===== HEADER ===== -->
            <header class="app-header top-bar">
               <nav class="navbar navbar-expand-md">
                  <div class="navbar-header d-flex align-items-center">
                     <a href="javascript:void(0)" class="mobile-toggle">
                     <i class="ti ti-align-right"></i>
                     </a>
                     <a class="navbar-brand" href="{{ url('/') }}">
                     <img src="{{ asset('assets/img/logo.png') }}" class="img-fluid logo-desktop" />
                     <img src="{{ asset('assets/img/logo-icon.png') }}" class="img-fluid logo-mobile" />
                     </a>
                  </div>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                  <i class="ti ti-align-left"></i>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                     <div class="navigation d-flex">
                        <ul class="navbar-nav nav-left">
                           <li class="nav-item">
                              <a href="javascript:void(0)" class="nav-link sidebar-toggle">
                              <i class="ti ti-align-right"></i>
                              </a>
                           </li>
                        </ul>
                        <ul class="navbar-nav nav-right ml-auto">
                           @guest
                           @if (Route::has('login'))
                           <li class="nav-item">
                              <a class="nav-link" href="{{ route('login') }}">Login</a>
                           </li>
                           @endif
                           @if (Route::has('register'))
                           <li class="nav-item">
                              <a class="nav-link" href="{{ route('register') }}">Register</a>
                           </li>
                           @endif
                           @endguest
                           @auth
                           <li class="nav-item dropdown user-profile">
                              <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown">
                              <img src="{{ Auth::user()->profile 
                                 ? asset('uploads/users/'.Auth::user()->profile) 
                                 : asset('assets/img/default-user.png') }}"
                                 id="previewImage"
                                 class="avatar-preview">
                               @if(Auth::user()->account_status != 0)
                                    <span class="bg-secondary user-status"></span>

                              @elseif(Auth::user()->is_online == 1)
                                    <span class="bg-success user-status"></span>

                              @elseif(Auth::user()->last_seen && now()->diffInMinutes(Auth::user()->last_seen) <= 5)
                                    <span class="bg-warning user-status"></span>

                              @else
                                    <span class="bg-danger user-status"></span>
                              @endif
                              </a>
                              <div class="dropdown-menu animated fadeIn">
                                 <div class="bg-gradient px-4 py-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                       <div class="mr-1">
                                          <h4 class="text-white mb-0">{{ Auth::user()->name }}</h4>
                                          <small class="text-white">{{ Auth::user()->email }}</small>
                                       </div>
                                       <a href="{{ route('logout') }}"
                                          class="text-white font-20"
                                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                       <i class="zmdi zmdi-power"></i>
                                       </a>
                                       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                          @csrf
                                       </form>
                                    </div>
                                 </div>
                                 <div class="p-4">
                                    <a class="dropdown-item d-flex nav-link" href="{{ route('profile') }}">
                                    <i class="fa fa-user pr-2 text-success"></i> Profile
                                    </a>                                   
                                   
                                 </div>
                              </div>
                           </li>
                           @endauth
                        </ul>
                     </div>
                  </div>
               </nav>
            </header>
           
            <div class="app-container">
               <!-- ===== SIDEBAR ===== -->
               <aside class="app-navbar">
                  <div class="sidebar-nav scrollbar scroll_light">
                     <ul class="metismenu" id="sidebarNav">
                        <!-- Dashboard -->
                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                           <a href="{{ url('/dashboard') }}">
                                 <i class="nav-icon ti ti-dashboard"></i>
                                 <span class="nav-title">Dashboard</span>
                           </a>
                        </li>

                        @if(in_array(auth()->user()->role, ['admin', 'hr']))

                        <!-- Users -->
                        <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                           <a class="has-arrow" href="javascript:void(0)">
                                 <i class="nav-icon ti ti-user"></i>
                                 <span class="nav-title">User</span>
                           </a>
                           <ul class="{{ request()->routeIs('users.*') ? 'in' : '' }}">
                                 <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                                    <a href="{{ route('users.index') }}">All Users</a>
                                 </li>
                                 <li class="{{ request()->routeIs('users.create') ? 'active' : '' }}">
                                    <a href="{{ route('users.create') }}">Add New User</a>
                                 </li>
                                 
                           </ul>
                        </li>
                        <li class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
                           <a class="has-arrow" href="javascript:void(0)">
                              <i class="nav-icon ti ti-user"></i>
                              <span class="nav-title">Clients</span>
                           </a>

                           <ul class="{{ request()->routeIs('clients.*') ? 'in' : '' }}">
                              <li class="{{ request()->routeIs('clients.index') ? 'active' : '' }}">
                                    <a href="{{ route('clients.index') }}">All Clients</a>
                              </li>

                              <li class="{{ request()->routeIs('clients.create') ? 'active' : '' }}">
                                    <a href="{{ route('clients.create') }}">Add New Client</a>
                              </li>
                           </ul>
                        </li>

                        <!-- Groups -->
                        <li class="{{ request()->routeIs('groups.*') ? 'active' : '' }}">
                           <a class="has-arrow" href="javascript:void(0)">
                                 <i class="nav-icon ti ti-id-badge"></i>
                                 <span class="nav-title">Group</span>
                           </a>
                           <ul class="{{ request()->routeIs('groups.*') ? 'in' : '' }}">
                                 <li class="{{ request()->routeIs('groups.index') ? 'active' : '' }}">
                                    <a href="{{ route('groups.index') }}">All</a>
                                 </li>
                                 <li class="{{ request()->routeIs('groups.create') ? 'active' : '' }}">
                                    <a href="{{ route('groups.create') }}">Add new Group</a>
                                 </li>
                           </ul>
                        </li>

                        @endif

                        <!-- Chat -->
                        <li class="{{ request()->routeIs('chat.*') ? 'active' : '' }}">
                           <a href="{{ route('chat.index') }}">
                                 <i class="nav-icon ti ti-comment"></i>
                                 <span class="nav-title">Chat</span>
                           </a>
                        </li>

                     </ul>
                  </div>
               </aside>
                          

               <!-- ===== MAIN CONTENT ===== -->
               <div class="app-main" id="main">
              
                     @yield('content')
                
                  
               </div>
            </div>
            <!-- ===== FOOTER ===== -->
                  <footer class="footer">                  
                     <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-left">
                           <p>&copy; {{ date('Y') }}. All rights reserved.</p>
                        </div>
                     </div>
                  </footer>
         </div>
      </div>
      <!-- JS -->
      <script src="{{ asset('assets/js/vendors.js') }}"></script>
      <script src="{{ asset('assets/js/app.js') }}"></script>
      <script src="{{ asset('assets/js/browser.js') }}"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      @yield('scripts')

   </body>
</html>