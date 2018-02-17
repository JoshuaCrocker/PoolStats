@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $team->name }} Statistics
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">Highest Performing Player</th>
                            <td>
                                @if ($team->highestPerformingPlayer == null)
                                    <em>Not calculated</em>
                                @else
                                    {{ $team->highestPerformingPlayer->name }}
                                    (
                                    {{ $team->highestPerformingPlayer->membership->member_from }}
                                    &mdash;
                                    {{ $team->highestPerformingPlayer->membership->member_to }}
                                    )
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $team->name }} Members

                        <a href="{{ url("{$team->endpoint()}/membership/create") }}"
                           class="btn btn-primary btn-xs pull-right">Add
                                                                     Member</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th style="width: 120px">Member From</th>
                                <th style="width: 120px">Member To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->link->member_from }}</td>
                                    <td>{{ $member->link->member_to }}</td>
                                    <td>
                                        <a href="{{ route('membership.edit', [
                                            'team' => $team,
                                            'membership' => $member->link
                                        ]) }}" class="btn btn-default btn-xs">Edit Membership</a>

                                        <form action="{{ $member->link->endpoint() }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <input type="submit" value="Terminate" class="btn btn-danger btn-xs"/>
                                        </form>
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
                        {{ $team->name }} Previous Members
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th style="width: 120px">Member From</th>
                                <th style="width: 120px">Member To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($historic as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->link->member_from }}</td>
                                    <td>{{ $member->link->member_to == null ? "Current" : $member->link->member_to }}</td>
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
