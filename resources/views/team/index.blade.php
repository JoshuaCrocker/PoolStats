@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Teams
                        <a href="{{ url('/teams/create') }}" class="btn btn-primary btn-xs pull-right">New Team</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Team Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($teams as $team)
                                <tr>
                                    <td>{{ $team->name }}</td>
                                    <td>// NYI</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
