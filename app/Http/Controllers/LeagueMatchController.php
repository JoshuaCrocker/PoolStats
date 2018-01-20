<?php

namespace App\Http\Controllers;

use App\League;
use App\LeagueMatch;
use Illuminate\Http\Request;

class LeagueMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('match.index', [
            'matches' => LeagueMatch::all()
        ]);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeagueMatch $leagueMatch
     * @return \Illuminate\Http\Response
     */
    public function show(LeagueMatch $leagueMatch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeagueMatch $leagueMatch
     * @return \Illuminate\Http\Response
     */
    public function edit(LeagueMatch $leagueMatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\LeagueMatch $leagueMatch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeagueMatch $leagueMatch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeagueMatch $leagueMatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeagueMatch $leagueMatch)
    {
        //
    }
}
