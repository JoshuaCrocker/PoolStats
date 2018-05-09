<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamVenue;
use App\Http\Requests\UpdateTeamVenue;
use App\TeamVenue;
use App\Team;
use App\Venue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeamVenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this
            ->middleware('auth')
            ->except('index', 'show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Venue $venue)
    {
        $teams = Team::all();

        return view('venue.membership.create', [
            'venue' => $venue,
            'today' => Carbon::now()->format('Y-m-d'),
            'teams' => $teams
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeamVenue $request, Venue $venue)
    {
        $team = Team::find($request->team_id);

        if (!is_null($team->venue)) {
            $old = TeamVenue::find($team->venue->link->id);
            $old->venue_to = $request->member_from;
            $old->save();
        }

        $membership = new TeamVenue();
        $membership->team_id = $request->team_id;
        $membership->venue_id = $venue->id;
        $membership->venue_from = $request->member_from;
        $membership->venue_to = empty($request->member_to) ? NULL : $request->member_to;
        $membership->save();

        return redirect($venue->endpoint());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TeamVenue  $teamVenue
     * @return \Illuminate\Http\Response
     */
    public function show(TeamVenue $teamVenue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TeamVenue  $teamVenue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue, TeamVenue $membership)
    {
        $teams = Team::all();

        return view('venue.membership.edit', [
            'membership' => $membership,
            'venue' => $venue,
            'today' => Carbon::now()->format('Y-m-d'),
            'teams' => $teams
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TeamVenue  $teamVenue
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeamVenue $request, Venue $venue, TeamVenue $membership)
    {
        $membership->venue_from = $request->member_from;
        $membership->venue_to = $request->member_to;
        $membership->save();

        return redirect($venue->endpoint());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TeamVenue  $teamVenue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue, TeamVenue $membership)
    {
        $membership->venue_to = Carbon::now();
        $membership->save();

        return redirect()->back();
    }
}
