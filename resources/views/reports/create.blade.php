@extends('master')

@section('content')
    <h1>Report a user</h1>
    <hr/>

    @if (isset($message))
        <div class="alert alert-success" role="alert">{{$message}}</div>
    @endif

    {!! Form::open(['url' => 'reports']) !!}
        <div class="report-form">
            <div class="form-group">
                {!! Form::label('profileUrl', 'Report URL:') !!}
                {!! Form::text('profileUrl', null, ['class' => 'form-control', 'placeholder' => 'https://plus.google.com/12345678987654321']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Submit Report for Review', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>
    {!! Form::close() !!}
@stop