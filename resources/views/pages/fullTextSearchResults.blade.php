@extends('layouts.appLogged')
@section('title', 'Full Text Search Results')

@section('content')

<section id="user-search-results">
    {{-- Make sure to use the correct variable here --}}
    @each('partials.user', $users, 'user')
</section>

@endsection
