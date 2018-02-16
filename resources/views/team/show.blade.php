@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $team->name }} Members
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th style="width: 120px">Member From</th>
                                <th style="width: 120px">Member To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->link->member_from }}</td>
                                    <td>{{ $member->link->member_to == null ? "Current" : $member->link->member_to }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $team->name }} Previous Members
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Member Name</th>
                                <th style="width: 120px">Member From</th>
                                <th style="width: 120px">Member To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($historic as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->link->member_from }}</td>
                                    <td>{{ $member->link->member_to == null ? "Current" : $member->link->member_to }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
