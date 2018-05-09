@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p><a href="{{ url()->previous() }}" class="btn btn-default">&laquo; Back</a></p>

                <div class="panel panel-default">
                    <div class="panel-heading">Matches &mdash; Edit Frame</div>

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

                        <form action="{{ route('frames.update', ['match' => $match->id, 'frame' => $frame->id]) }}"
                              method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}

                            <div class="form-group">
                                <label for="frame_type">Frame Type</label>
                                <select name="frame_type" id="frame_type" class="form-control">
                                    <option value="single" {{ $frame->doubles ? '' : 'selected' }}>Single</option>
                                    <option value="double" {{ $frame->doubles ? 'selected' : '' }}>Double</option>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="home_player_id_1">Home Player 1</label>
                                    <select name="home_player_id[]" id="home_player_id_1" class="form-control">
                                        <option value="">Select Home Player</option>
                                        @foreach ($homePlayers as $player)
                                            <option value="{{ $player->id }}" {{ $frame->homePlayers[0]->id == $player->id ? 'selected' : '' }}>
                                                {{ $player->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="away_player_id_1">Away Player 1</label>
                                    <select name="away_player_id[]" id="away_player_id_1" class="form-control">
                                        <option value="">Select Away Player</option>
                                        @foreach ($awayPlayers as $player)
                                            <option value="{{ $player->id }}" {{ $frame->awayPlayers[0]->id == $player->id ? 'selected' : '' }}>
                                                {{ $player->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row" id="double_player_row">
                                <div class="form-group col-md-6">
                                    <label for="home_player_id_2">Home Player 2</label>
                                    <select name="home_player_id[]" id="home_player_id_2" class="form-control">
                                        <option value="">Select Home Player</option>
                                        @foreach ($homePlayers as $player)
                                            <option value="{{ $player->id }}" {{ $frame->homePlayers[1]->id == $player->id ? 'selected' : '' }}>
                                                {{ $player->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="away_player_id_2">Away Player 2</label>
                                    <select name="away_player_id[]" id="away_player_id_2" class="form-control">
                                        <option value="">Select Away Player</option>
                                        @foreach ($awayPlayers as $player)
                                            <option value="{{ $player->id }}" {{ $frame->awayPlayers[1]->id == $player->id ? 'selected' : '' }}>
                                                {{ $player->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="winning_team">Winning Team</label>
                                <select name="winning_team" id="winning_team" class="form-control">
                                    <option value="home" {{ $frame->isWinner($frame->homePlayer) ? 'selected' : '' }}>
                                        Home
                                    </option>
                                    <option value="away" {{ $frame->isWinner($frame->awayPlayer) ? 'selected' : '' }}>
                                        Away
                                    </option>
                                </select>
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

@section('scripts')
    <script>
        (function () {
            var frameTypeDrowdown = $("#frame_type");

            frameTypeDrowdown.change(function () {
                var me = $(this);
                var doublePlayerRow = $("#double_player_row")

                if (me.val() === 'single') {
                    doublePlayerRow.hide().find('select').attr('disabled', 'disabled');
                } else {
                    doublePlayerRow.show().find('select').removeAttr('disabled');
                }
            });

            frameTypeDrowdown.change();
        })();
    </script>
@endsection
