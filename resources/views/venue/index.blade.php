@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Venues
                        <a href="{{ route('venues.create') }}" class="btn btn-primary btn-xs pull-right">New Venue</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($venues as $venue)
                                <tr>
                                    <td>{{ $venue->name }}</td>
                                    <td>
                                        <a href="{{ route('venues.show', $venue) }}" class="btn btn-default btn-xs">View
                                                                                                                      Details</a>
                                        <a href="{{ route('venues.edit', $venue) }}" class="btn btn-default btn-xs">Edit</a>

                                        <form action="{{ route('venues.destroy', $venue) }}" method="POST">
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
