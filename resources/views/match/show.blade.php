@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <p><a href="{{ route('matches.index') }}" class="btn btn-default">&laquo; Back</a></p>

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
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($match->frames as $frame)
                                @if (view()->exists("match.frame.{$frame->type}"))
                                    @include ("match.frame.{$frame->type}", ['frame' => $frame])
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center"><em>No Records</em></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
