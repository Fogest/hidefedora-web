@extends('master')

@section('content')
    <h1>Reports</h1>
    <hr/>
    <table class="table table-hover table-bordered review">
        <thead>
        <tr>
            <th class="id">Name</th>
            <th class="comment">Comment</th>
            <th class="time">Time</th>
            <th class="weight">Weight</th>
            <th class="decision">Approve/Reject</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reports as $report)
            <tr>
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
                <td class="time">{{\Carbon\Carbon::createFromTimestamp(strtotime($report->created_at))->diffForHumans()}}</td>
                <td class="weight">{{$report->rep}}</td>
                <td class="decision">
                    <button class="btn btn-success approve" type="button" name="{{$report->id}}">
                        Approve
                    </button>
                    <button class="btn btn-danger reject" type="button" name="{{$report->id}}">
                        Reject
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('scripts')
    <script type="text/javascript">
        $("button.approve").click(function(){
            var id = $(this).attr("name");
            var button = $(this);
            $.post("{{action("ReportsController@update")}}",{status:1, id:id},function(result){
                button.closest("tr").removeClass("danger").addClass("success");
            });
        });
        $("button.reject").click(function(){
            var id = $(this).attr("name");
            var button = $(this);
            $.post("{{action("ReportsController@update")}}",{status:-1, id:id},function(result){
                button.closest("tr").removeClass("success").addClass("danger");
            });
        });
    </script>
@stop