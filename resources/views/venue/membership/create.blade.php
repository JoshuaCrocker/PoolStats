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

                        <form action="{{ route('venues.membership.index', $venue) }}" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="team_id">Team</label>
                                <select name="team_id" id="team_id" class="form-control">
                                    <option value="">Select Team</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                                data-venue-id="{{ $team->venue ? $team->venue->id : "" }}"
                                                data-venue-name="{{ $team->venue ? $team->venue->name : "" }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="alert alert-warning" id="terminate-warning">
                                <strong>Warning</strong>! Adding this team to <strong>{{ $venue->name }}</strong> will
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

            $("#team_id").change(function () {
                var me = $(this);
                var opt = me.children(':selected');

                if (opt.data('venue-id')) {
                    current_team.html(opt.data('venue-name'));
                    termination_warning.show();
                } else {
                    termination_warning.hide();
                }
            });
        })();
    </script>
@endsection
