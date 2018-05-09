@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p><a href="{{ url()->previous() }}" class="btn btn-default">&laquo; Back</a></p>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $venue->name }} Upcoming Matches
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <th>Match Name</th>
                            <th>Match Date</th>
                            <th>Actions</th>
                        </thead>

                        <tbody>
                            @foreach ($matches as $match)
                                <tr>
                                    <td>{{ $match->homeTeam->name }} vs. {{ $match->awayTeam->name }}</td>
                                    <td>{{ $match->match_date }}</td>
                                    <td>
                                        <a href="{{ $match->endpoint() }}"
                                           class="btn btn-default btn-xs">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $venue->name }} Teams

                        <a href="{{ url("{$venue->endpoint()}/membership/create") }}"
                           class="btn btn-primary btn-xs pull-right">Add
                                                                     Team</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th style="width: 120px">Member From</th>
                                <th style="width: 120px">Member To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td>{{ $member['team']->name }}</td>
                                    <td>{{ $member['link']->venue_from }}</td>
                                    <td>{{ $member['link']->venue_to }}</td>
                                    <td>
                                        <a href="{{ route('venues.membership.edit', [
                                            'venue' => $venue,
                                            'membership' => $member['link']
                                        ]) }}" class="btn btn-default btn-xs">Edit Membership</a>

                                        @if (!$member['link']->terminates_today)
                                        <form action="{{ url($member['link']->endpoint()) }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <input type="submit" value="Terminate" class="btn btn-danger btn-xs"/>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $venue->name }} Previous Teams
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th style="width: 120px">Member From</th>
                                <th style="width: 120px">Member To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($historic as $member)
                                <tr>
                                    <td>{{ $member['team']->name }}</td>
                                    <td>{{ $member['link']->venue_from }}</td>
                                    <td>{{ $member['link']->venue_to }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
