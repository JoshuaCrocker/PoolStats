@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $league->name }}
                    </div>

                    {{--<table class="table table-bordered">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                    {{--<th>Member Name</th>--}}
                    {{--<th>Actions</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}

                    {{--<tbody>--}}
                    {{--@foreach ($members as $member)--}}
                    {{--<tr>--}}
                    {{--<td>{{ $member->name }}</td>--}}
                    {{--<td></td>--}}
                    {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                    {{--</table>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
