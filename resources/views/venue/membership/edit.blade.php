@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p><a href="{{ route('venues.show', $venue) }}" class="btn btn-default">&laquo; Back</a></p>

                <div class="panel panel-default">
                    <div class="panel-heading">Venues &mdash; Add Team</div>

                    <div class="panel-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('venues.membership.update', [
                            'venue' => $venue,
                            'memvership' => $membership]) }}" method="POST">
                            {{ csrf_field() }}
                            @method('PATCH')

                            <input type="hidden" name="team_id" value="{{ $membership->team_id }}">

                            <div class="form-group">
                                <label for="member_from">Member From</label>
                                <input type="date" id="member_from" name="member_from" value="{{ $membership->venue_from }}"
                                       class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="member_to">Member To</label>
                                <input type="date" id="member_to" name="member_to" value="{{ $membership->venue_to }}" class="form-control">
                                <p class="help-block">Leave blank if the end date isn't yet known.</p>
                            </div>

                            <div class="form-group">
                                <input type="submit" value="Save" class="btn btn-primary"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
