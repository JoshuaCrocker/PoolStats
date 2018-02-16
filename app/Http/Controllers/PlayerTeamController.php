<?php

namespace App\Http\Controllers;

use App\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlayerTeamController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PlayerTeam  $playerTeam
     * @return \Illuminate\Http\Response
     */
    public function show(PlayerTeam $playerTeam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PlayerTeam  $playerTeam
     * @return \Illuminate\Http\Response
     */
    public function edit(PlayerTeam $playerTeam)
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
    public function update(Request $request, PlayerTeam $playerTeam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PlayerTeam $playerteam
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlayerTeam $playerteam)
    {
        $playerteam->member_to = Carbon::parse('-1 day');
        $playerteam->save();

        return redirect()->back();
    }
}
