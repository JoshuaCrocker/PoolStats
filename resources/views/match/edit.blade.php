@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Matches</div>

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

                        <form action="{{ route('matches.update', ['match' => $match]) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}

                            <div class="form-group">
                                <label for="league_id">League</label>
                                <select name="league_id" id="league_id" class="form-control">
                                    @foreach ($leagues as $league)
                                        <option value="{{ $league->id }}" {{ $match->league_id == $league->id ? 'selected' : '' }}>{{ $league->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="match_date">Match Date</label>
                                <input type="date" name="match_date" id="match_date"
                                       value="{{ $match->match_date->toDateString() }}"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="home_team_id">Home Team</label>
                                <select name="home_team_id" id="home_team_id" class="form-control">
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" {{ $match->home_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="away_team_id">Away Team</label>
                                <select name="away_team_id" id="away_team_id" class="form-control">
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}" {{ $match->away_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-primary"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
