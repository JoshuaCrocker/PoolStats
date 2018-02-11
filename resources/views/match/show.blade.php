@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $match->name }}

                        <a href="{{ route('frames.create', ['match' => $match->id]) }}"
                           class="pull-right btn btn-primary btn-xs">New Frame</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">Match Frames</th>
                            </tr>

                            <tr>
                                <th class="text-center" style="width: 20px">#</th>
                                <th>Home Team</th>
                                <th>Away Team</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($match->frames as $frame)
                                @if (view()->exists("match.frame.{$frame->type}"))
                                    @include ("match.frame.{$frame->type}", ['frame' => $frame])
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
