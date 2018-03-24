@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $player->name }} Statistics
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">Overall Wins / Loses</th>
                            <td>
                                @if (is_null($stat_wld))
                                    <em>Stat not yet calculated.</em>
                                @else
                                    <strong class="text-success">{{ $stat_wld->wins }}<abbr
                                                title="Wins">W</abbr></strong>
                                    /
                                    <strong class="text-danger">{{ $stat_wld->loses }}<abbr
                                                title="Loses">L</abbr></strong>
                                    /
                                    <strong class="text-gray">{{ $stat_wld->draws }}<abbr
                                                title="Draws">D</abbr></strong>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Match Attendance</th>
                            <td>
                                @if (is_null($stat_attendance))
                                    <em>Stat not yet calculated.</em>
                                @else
                                    {{ $stat_attendance->percentage }}%
                                    ({{ $stat_attendance->played }} / {{ $stat_attendance->total }})
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $player->name }} Venue Performance
                    </div>

                    <table class="table table-bordered">
                        @if (is_null($performance))
                        <tbody>
                            <td>
                                <em>Stat not yet calculated.</em>
                            </td>
                        </tbody>
                        @else
                        <thead>
                            <tr>
                                <th>Venue Name</th>
                                <th>% Won</th>
                                <th># Won</th>
                                <th># Played</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($performance as $record)
                                <tr>
                                    <td>{{ $record->venue->name }}</td>
                                    <td>{{ $record->percentage }}%</td>
                                    <td>{{ $record->won }}</td>
                                    <td>{{ $record->played }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $player->name }} Memberships
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th>Member From</th>
                                <th>Member To</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($player->memberships as $membership)
                                <tr>
                                    <td>{{ $membership->team->name }}</td>
                                    <td>{{ $membership->member_from }}</td>
                                    <td>{{ $membership->member_to ?: "Current" }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
