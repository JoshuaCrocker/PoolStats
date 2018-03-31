@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Players
                        <a href="{{ url('/players/create') }}" class="btn btn-primary btn-xs pull-right">New Player</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($players as $player)
                                <tr>
                                    <td>{{ $player->name }}</td>
                                    <td>
                                        <a href="{{ route('players.show', $player) }}" class="btn btn-default btn-xs">View
                                                                                                                      Details</a>
                                        <a href="{{ route('players.edit', $player) }}" class="btn btn-default btn-xs">Edit</a>

                                        <form action="{{ route('players.destroy', $player) }}" method="POST">
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
