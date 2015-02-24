@extends('master')
@section('content')
    <h1>Access Denied</h1>
    @if (Auth::check())
        <p>You are logged in, however you do not have the required permissions to view this page.</p>
    @else
        <p>You are not logged in. Please login before attempting to access this page.</p>
    @endif
@stop