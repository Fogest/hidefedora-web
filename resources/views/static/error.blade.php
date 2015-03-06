@extends('master')
@section('content')
    <h1>Error</h1>
    @if (isset($message))
        @if (isset($status))
            @if ($status == 'error')
                <div class="alert alert-danger" role="alert">{!! $message !!}</div>
            @endif
            @else
            <div class="alert alert-success" role="alert">{!! $message !!}</div>
        @endif
    @endif
    <p>There has been an error!</p>
@stop