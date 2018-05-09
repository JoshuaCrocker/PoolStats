@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p><a href="{{ route('teams.show', $team) }}" class="btn btn-default">&laquo; Back</a></p>

                <div class="panel panel-default">
                    <div class="panel-heading">Teams &mdash; Edit Membership</div>

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

                        <form action="{{ route('membership.update', [$team, $membership]) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}

                            <div class="form-group">
                                <label for="member_from">Member From</label>
                                <input type="date" id="member_from" name="member_from"
                                       value="{{ $membership->member_from }}"
                                       class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="member_to">Member To</label>
                                <input type="date" id="member_to" name="member_to" value="{{ $membership->member_to }}"
                                       class="form-control">
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
