@extends('master')

@section('content')
    @if(isset($num))
        <h1><span id="reportNum">{{$num}}</span> Reports</h1>
    @else
    <h1>0 Reports</h1>
    @endif
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
                        <a class="displayName" target="_blank" href="https://plus.google.com/{{$report->profileId}}">{{$report->displayName}}</a>
                    @else
                        <a class="displayName" target="_blank" href="https://plus.google.com/{{$report->profileId}}">{{$report->profileId}}</a>
                    @endif
                    @if (!is_null($report->youtubeUrl))
                        <a class="youtubeUrl" target="_blank" href="{{$report->youtubeUrl}}">(^)</a>
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
        var reports = {{$count}};
        var shown = {{$count}};
        var total = {{$num}};
        $("button.approve").click(function(){
            var id = $(this).attr("name");
            var button = $(this);
            $.post("{{action("ReportsController@update")}}",{status:1, id:id},function(result){
                button.closest("tr").removeClass("danger").addClass("success").remove();
            });
        });
        $("button.reject").click(function(){
            var id = $(this).attr("name");
            var button = $(this);
            $.post("{{action("ReportsController@update")}}",{status:-1, id:id},function(result){
                button.closest("tr").removeClass("success").addClass("danger").remove();
            });
        });

        $("button").click(function(){
            var row = $("table.review tr:last").clone(true);
            $.get("{{action("ReportsController@newRows")}}",{
                count: shown
            }, function(res) {
                res = jQuery.parseJSON(res);
                if(typeof res[0] !== 'undefined') {
                    row.find('.id img').attr("src", res[0].profilePictureUrl);
                    row.find('.id img').attr("alt", res[0].displayName);
                    row.find('.id a.displayName').text(res[0].displayName);
                    row.find('.id a.displayName').attr("href", "https://plus.google.com/" + res[0].profileId);
                    row.find('.id a.youtubeUrl').attr("href", res[0].youtubeUrl);
                    row.find('.comment').text(res[0].comment);
                    row.find('.time').text(res[0].time);
                    row.find('.weight').text(res[0].weight);
                    row.find('.btn.approve').attr("name", res[0].id);
                    row.find('.btn.reject').attr("name", res[0].id);
                    row.insertAfter("table.review tr:last");
                }
            });
            total--;
            $("#reportNum").text(total);
            shown++;

        });
    </script>
@stop