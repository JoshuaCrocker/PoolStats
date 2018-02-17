<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerTeam;
use App\Player;
use App\PlayerTeam;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlayerTeamController extends Controller
{
    public function __construct()
    {
        $this
            ->middleware('auth')
            ->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function create(Team $team)
    {
        $data = [
            'team' => $team,
            'today' => date('Y-m-d'),
            'players' => Player::all()
        ];

        return view('team.membership.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePlayerTeam $request
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlayerTeam $request, Team $team)
    {
        $membership = new PlayerTeam();
        $membership->player_id = $request->player_id;
        $membership->team_id = $team->id;
        $membership->member_from = $request->member_from;
        $membership->member_to = empty($request->member_to) ? NULL : $request->member_to;
        $membership->save();

        return redirect($team->endpoint());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PlayerTeam  $playerTeam
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team, PlayerTeam $membership)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PlayerTeam  $playerTeam
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team, PlayerTeam $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PlayerTeam  $playerTeam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team, PlayerTeam $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PlayerTeam $membership
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team, PlayerTeam $membership)
    {
        $membership->member_to = Carbon::parse('-1 day');
        $membership->save();

        return redirect()->back();
    }
}
