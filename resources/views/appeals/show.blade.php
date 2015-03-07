@extends('master')

@section('content')
    @if (isset($id))

        <h1>Appeal Id#{{$id}}
        <div class="status">
            @if ($status == -1)
                <span class="label label-danger">Rejected</span>
            @elseif ($status == 1)
                <span class="label label-success">Accepted</span>
            @else
                <span class="label label-default">Pending</span>
            @endif
        </div></h1>
        <hr/>
        <div class="container appealsResults">
            <div class="profileId"><span class="descriptor">Profile ID:</span> {{$profileId}}</div>

            @if (!Auth::guest())
                @if (Auth::user()->user_level > 1)
                    <div class="userComment"><span class="descriptor">User Comment:</span> <div class="well">{{$comment}}</div></div>
                @endif
            @endif

            @if ($status != 0)
                <div class="response"><span class="descriptor">Staff Response:</span> <div class="well">{{$response}}</div></div>
            @endif

            <div class="submitted"><span class="descriptor">Submitted at</span> {{\Carbon\Carbon::createFromTimestamp(strtotime($created_at))->toDayDateTimeString()}}</div>
        </div>

        @if (!Auth::guest())
            @if (Auth::user()->user_level > 1)
                <hr/>
                <h2>Admin Controls</h2>
                {!! Form::open(['url' => "appeal/$id"]) !!}
                <div class="appeal-form">
                    <!-- Status Form Input -->
                    <div class="form-group">
                        {!! Form::label('status', 'Status:') !!}
                        {!! Form::select('status', ['Approve', 'Silent Approve', 'Reject', 'Silent Reject', ], ['class' => 'form-control', 'placeholder' => 'NOOOOO, never getting appealed!']) !!}
                    </div>

                    <!-- Response Form Input -->
                    <div class="form-group">
                        {!! Form::label('response', 'Response:') !!}
                        {!! Form::textarea('response', null, ['class' => 'form-control', 'placeholder' => 'NOOOOO, never getting appealed!']) !!}
                    </div>

                    {!! Form::hidden('appealId', $id) !!}
                    {!! Form::hidden('profileId', $profileId) !!}

                    <!-- Submit - Button -->
                    <div class="form-group">
                        {!! Form::submit('Submit Response', ['class' => 'btn btn-primary form-control']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            @endif
        @endif
    @else
        <h1>Error!</h1>
        <hr/>
        <div class="alert alert-danger" role="alert">Couldn't find an appeal with that value.</div>
    @endif
@stop