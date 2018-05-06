<?php

namespace App\Http\Controllers;

use App\LeagueFramePlayer;
use Illuminate\Http\Request;

class LeagueFramePlayerController extends Controller
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
     * @param  \App\LeagueFramePlayer  $leagueFramePlayer
     * @return \Illuminate\Http\Response
     */
    public function show(LeagueFramePlayer $leagueFramePlayer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeagueFramePlayer  $leagueFramePlayer
     * @return \Illuminate\Http\Response
     */
    public function edit(LeagueFramePlayer $leagueFramePlayer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeagueFramePlayer  $leagueFramePlayer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeagueFramePlayer $leagueFramePlayer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeagueFramePlayer  $leagueFramePlayer
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeagueFramePlayer $leagueFramePlayer)
    {
        //
    }
}
