<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    
    <body>
        <main>
            <aside id="left-bar">
                <div class="upper-bar">
                    <div class="logo">
                        <!-- image is one public/assets/skillswap_white_grey.svg -->
                        <a href="{{ url('/home') }}">
                                <img src="{{ url('assets/skillswap.png') }}"/>
                        </a>
                    </div>
                    <nav>
                    <ul>

                        <li>
                            <a href="{{ url('/home') }}">
                                <span class="material-symbols-outlined">
                                home
                                </span>Home
                            </a>
                        </li>

                        
                        @if (Auth::user())
                            <li>
                                <a href="{{ route('user', ['username' => Auth::user()->username]) }}" > 
                                    <span class="material-symbols-outlined">
                                        account_circle
                                        </span>Profile
                                </a>
                            </li>
                        @endif    
                        <li>
                            <a>
                                <span class="material-symbols-outlined">
                                settings
                                </span>Settings
                            </a>
                        </li>
                        @if (Auth::user())
                            <li>
                                <a href="{{ route ('groups') }}">

                                    <span class="material-symbols-outlined">
                                    groups
                                    </span>Groups
                                </a>
                            </li>
                        @endif    
                        
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropbtn">
                                <span class="material-symbols-outlined">
                                expand_more
                                </span>See More
                            </a>
                            <div class="dropdown-content">
                                <a href="{{ route('mainFeatures') }}">Main Features</a>
                                <a href="{{ route('about') }}">About Us</a>
                                <a href="{{ route('about') }}">Contact Us</a>
                            </div>
                        </li>
                        
                    </ul>
                    </nav>
                </div>
                @if (Auth::check())
                    <a class="button" href="{{ url('/logout') }}"> Logout </a>

                @else
                    <a class="button" href="{{ url('/login') }}"> Login </a>
                @endif
            </aside>

            <section id="content">
                <div class="search">
                    <form action="{{ url('/search') }}" method="GET">
                        <span class="material-symbols-outlined">
                            search
                        </span>
                        <input type="text" name="q" placeholder="Search" value="{{ $query ?? '' }}" autofocus>
                        <input type="hidden" name="type" value="{{ $type ?? 'user' }}">
                        <input type="hidden" name="date" value="{{ $date ?? 'asc' }}">
                        <input type="hidden" name="popularity" value="{{ $popularity ?? 'asc' }}">
                        <input type="submit" value="Search" style="display: none;">
                    </form>
                </div>
                @yield('content')
            </section>
            
            <aside id="right-bar">
                <div class="notifications-bar">
                    <ul>
                        <li>
                            <a id="notifications" href="javascript:void(0)">
                                <span class="material-symbols-outlined">
                                    arrow_drop_down
                                </span>
                                Notifications
                                @if(Auth::user() && Auth::user()->hasUnreadNotifications())
                                    <!-- dot -->
                                    <span class="material-symbols-outlined new-notification">
                                        fiber_manual_record
                                    </span>
                                @endif
                            </a>
                        </li>
                    </ul>

                    <div class="notifications">
                        @if (Auth::check())
                            @if(Auth::user()->notifications->isEmpty())
                                <p>You have no notifications</p>
                            @else
                                @each('partials.notification', Auth::user()->notifications->sortByDesc('date'), 'notification')
                            @endif
                        @else
                            <p>Login to check your notifications</p>
                        @endif
                    </div>
                </div>
                
                <a class="button mark-as-read" href="javascript:void(0)"> Mark all as read </a>
            </aside>
        </main>
    </body>
</html>