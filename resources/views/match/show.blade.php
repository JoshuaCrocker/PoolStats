@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $match->name }}
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
                                <tr>
                                    <td class="text-center">{{ $frame->frame_number }}</td>
                                    <td>
                                        {{ $frame->homePlayer->name }}

                                        @if ($frame->isWinner($frame->homePlayer))
                                            [W]
                                        @endif
                                    </td>
                                    <td>
                                        {{ $frame->awayPlayer->name }}

                                        @if ($frame->isWinner($frame->awayPlayer))
                                            [W]
                                        @endif
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
