@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
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
    </div>
@endsection
