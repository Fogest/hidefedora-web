@extends('master')

@section('content')
    <h1>History</h1>
    <hr/>
    <table class="table table-hover table-bordered review">
        <thead>
        <tr>
            <th class="id">Name</th>
            <th class="comment">Comment</th>
            <th class="date_submitted">Submission Date</th>
            <th class="date_approved">Approved</th>
            <th class="approvedBy">Approving User</th>
            @if(Auth::user()->user_level > 1)
                <th class="action"></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($reports as $report)
            @if ($report->approvalStatus == 1)
                <tr class="success">
            @else
                <tr class="danger">
            @endif
                <td class="id">
                    @if (!is_null($report->profilePictureUrl))
                        <img src="{{$report->profilePictureUrl}}" alt="{{$report->displayName}}" title="{{$report->profileId}}">
                    @endif
                    @if (!is_null($report->displayName))
                        <a target="_blank" href="https://plus.google.com/{{$report->profileId}}">{{$report->displayName}}</a>
                    @else
                        <a target="_blank" href="https://plus.google.com/{{$report->profileId}}">{{$report->profileId}}</a>
                    @endif
                    @if (!is_null($report->youtubeUrl))
                        <a target="_blank" href="{{$report->youtubeUrl}}">(^)</a>
                    @endif
                </td>
                <td class="comment">{{$report->comment}}</td>
                <td class="date_approved">{{\Carbon\Carbon::createFromTimestamp(strtotime($report->updated_at))->toDayDateTimeString()}}</td>
                <td class="date_approved">{{\Carbon\Carbon::createFromTimestamp(strtotime($report->updated_at))->diffForHumans()}}</td>
                <td class="approvedBy">{{$report->approvingUser}}</td>
                @if(Auth::user()->user_level > 1)
                    <td class="action">
                        <button class="btn btn-warning undo" type="button" name="{{$report->id}}">
                            Undo
                        </button>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@if(Auth::user()->user_level > 1)
    @section('scripts')
        <script type="text/javascript">
            $("button.undo").click(function(){
                var id = $(this).attr("name");
                var button = $(this);
                $.post("{{action("ReportsController@update")}}",{status:0, id:id},function(result){
                    button.closest("tr").removeClass("danger").removeClass("success").addClass("warning");
                });
            });
        </script>
    @stop
@endif