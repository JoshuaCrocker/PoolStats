@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Leagues
                        <a href="{{ url('/leagues/create') }}" class="btn btn-primary btn-xs pull-right">New League</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>League Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($leagues as $league)
                                <tr>
                                    <td>{{ $league->name }}</td>
                                    <td>
                                        <a href="{{ $league->endpoint() }}" class="btn btn-default btn-xs">View Details</a>
                                        <a href="{{ $league->endpoint() }}/edit" class="btn btn-default btn-xs">Edit</a>

                                        <form action="{{ $league->endpoint() }}" method="POST">
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
