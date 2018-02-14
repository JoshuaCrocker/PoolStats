@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Matches
                        <a href="{{ route('matches.create') }}" class="btn btn-primary btn-xs pull-right">New Match</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Match Name</th>
                                <th>Score</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($matches as $match)
                                <tr>
                                    <td>{{ $match->name }}</td>
                                    <td>{{ $match->homePoints }}-{{ $match->awayPoints }}</td>
                                    <td>
                                        <a href="{{ $match->endpoint() }}" class="btn btn-default btn-xs">View Details</a>
                                        <a href="{{ $match->endpoint() }}/edit" class="btn btn-default btn-xs">Edit</a>

                                        <form action="{{ $match->endpoint() }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <input type="submit" value="Delete" class="btn btn-danger btn-xs"/>
                                        </form>
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
