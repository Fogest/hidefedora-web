@extends('master')

@section('content')
    <h1>History</h1>

    <table class="table table-hover table-bordered review">
        <thead>
        <tr>
            <th class="id">Name</th>
            <th class="comment">Comment</th>
            <th class="date_submitted">Submission Date</th>
            <th class="date_approved">Approved</th>
            <th class="approvedBy">Approving User</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reports as $report)
            @if ($report->approvalStatus == 1)
                <tr class="success">
            @else
                <tr class="danger">
            @endif
                <td class="id">{{$report->profileId}}</td>
                <td class="comment">{{$report->comment}}</td>
                <td class="date_approved">{{\Carbon\Carbon::createFromTimestamp(strtotime($report->updated_at))->toFormattedDateString()}}</td>
                <td class="date_approved">{{\Carbon\Carbon::createFromTimestamp(strtotime($report->updated_at))->diffForHumans()}}</td>
                <td class="approvedBy">{{$report->approvingUser}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop