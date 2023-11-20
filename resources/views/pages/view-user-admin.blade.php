@extends('layouts.appLoggedAdmin')

@section('title', 'User')

@section('content')


 
<!-- Profile Section -->
<section id="profile" class="profile-section">
    <!-- Profile Header with Background Image -->
    <div class="profile-header">       
        <div class="header-background">
            <img src="{{url('assets/blob-background.jpg')}}" alt="Background Picture">
        </div>

        <!-- Profile Picture -->
        <div class="profile-picture">
            @if($user->profile_picture)
            <img src="{{stream_get_contents($user->profile_picture)}}"/>
            @else
            <img src="{{ url('assets/profile-picture.png') }}"/>
            @endif
        </div>
        <!-- Profile Info -->
        <div class="profile-info">
            <h1 class="user-name">{{ $user->name }}</h1>
            <p class="user-title">{{ $user->email }}</p>
            <a href="{{ route('edit-user-form-admin', ['username' => $user->username]) }}" class="button">
                Edit Profile
            </a>
        </div>

        <!-- Edit Button -->
        

    </div>
    <!-- Profile Content Grid -->
    <div class="profile-content">
        <!-- Friends and Groups Grid -->
        <div class="friends-groups-grid">
            <!-- Friends Box -->
            <div class="friends-box">
                <h2>Friends</h2>
                @foreach ($user->get_friends() as $user)
                <div class="user-card">
                    <a href="{{ route('view-user-admin', ['username' => $user->username]) }}">
                        @if($user->profile_picture)
                        <img src="{{stream_get_contents($user->profile_picture)}}"/>
                        @else
                        <img src="{{ url('assets/profile-picture.png') }}"/>
                        @endif
                        {{$user->username}}
    
                    </a>
    
                </div>
                @endforeach
            </div>
            <!-- Groups Box -->
            <div class="groups-box">
                <h2>Groups</h2>
                @foreach ($user->get_groups() as $group)
                <div class="user-card">
                    <a href="">
                        @if($group->profile_picture)
                        <img src="{{stream_get_contents($user->profile_picture)}}"/>
                        @else
                        <img src="{{ url('assets/group.png') }}"/>
                        @endif
                        {{$group->name}}
                    </a>
    
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Posts Section -->
        <section id="posts">
            <h2>Posts</h2>
            @each('partials.post-admin', $posts, 'post')
        </section>
    </div>
</section>

@endsection


