@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p><a href="{{ route('teams.show', $team) }}" class="btn btn-default">&laquo; Back</a></p>

                <div class="panel panel-default">
                    <div class="panel-heading">Teams &mdash; Add Member</div>

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

                        <form action="{{ route('membership.index', $team) }}" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="player_id">Player</label>
                                <select name="player_id" id="player_id" class="form-control">
                                    <option value="">Select Player</option>
                                    @foreach ($players as $player)
                                        <option value="{{ $player->id }}"
                                                data-team-id="{{ $player->team ? $player->team->id : "" }}"
                                                data-team-name="{{ $player->team ? $player->team->name : "" }}">{{ $player->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="alert alert-warning" id="terminate-warning">
                                <strong>Warning</strong>! Adding this member to <strong>{{ $team->name }}</strong> will
                                                        terminate their membership with <strong
                                        id="current-team"></strong> (effective from <em>Member From</em> date).
                            </div>

                            <div class="form-group">
                                <label for="member_from">Member From</label>
                                <input type="date" id="member_from" name="member_from" value="{{ $today }}"
                                       class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="member_to">Member To</label>
                                <input type="date" id="member_to" name="member_to" class="form-control">
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

@section('scripts')
    <script>
        (function () {
            var termination_warning = $("#terminate-warning");
            var current_team = $("#current-team");

            termination_warning.hide();

            $("#player_id").change(function () {
                var me = $(this);
                var opt = me.children(':selected');

                if (opt.data('team-id')) {
                    current_team.html(opt.data('team-name'));
                    termination_warning.show();
                } else {
                    termination_warning.hide();
                }
            });
        })();
    </script>
@endsection
