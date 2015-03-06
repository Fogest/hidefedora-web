@extends('master')

@section('content')
    <h1>Appeal a ban</h1>
    <hr/>

    @if (isset($message))
        @if (isset($status))
            @if ($status == 'error')
                <div class="alert alert-danger" role="alert">{!! $message !!}</div>
            @endif
        @else
            <div class="alert alert-success" role="alert">{!! $message !!}</div>
        @endif
    @endif

    <p>The appeal process is simple. Insert your profile id that you are appealing, and a comment regarding why
    you should be unbanned. You can also optionally enter an email address and we will email you when your report
    has been reviewed. Email or not you will be given a link on submission which you can use to review your
    appeals results. <strong>For privacy reasons this page will not contain your email address or comment you made,
    only the profile id!</strong></p><br/>

    {!! Form::open(['url' => 'appeal']) !!}
    <div class="appeal-form">
        <!-- Profile ID Form Input -->
        <div class="form-group">
            {!! Form::label('profileId', 'Profile ID:') !!}
            {!! Form::text('profileId', null, ['class' => 'form-control', 'placeholder' => '1234567890124312']) !!}
        </div>

        <!-- Comment Form Input -->
        <div class="form-group">
            {!! Form::label('comment', 'Comment:') !!}
            {!! Form::textarea('comment', null, ['class' => 'form-control', 'placeholder' => 'I am a good boy!']) !!}
        </div>

        <!-- Email Form Input -->
        <div class="form-group">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'bob@example.com']) !!}
        </div>

        <!-- Submit - Button -->
        <div class="form-group">
            {!! Form::submit('Submit Appeal', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@stop