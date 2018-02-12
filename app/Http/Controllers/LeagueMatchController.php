<?php

namespace App\Http\Controllers;

use App\League;
use App\LeagueMatch;
use App\Team;
use Illuminate\Http\Request;

class LeagueMatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

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
        $data = [
            'leagues' => League::all(),
            'teams' => Team::all()
        ];

        return view('match.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'league_id' => 'required|exists:leagues,id',
            'match_date' => 'required|date',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id'
        ]);

        if (! $request->exists('venue_id')) {
            $request->venue_id = Team::find($request->home_team_id)->venue->id;
        }

        $match = new LeagueMatch();
        $match->league_id = $request->league_id;
        $match->venue_id = $request->venue_id;
        $match->match_date = $request->match_date;
        $match->home_team_id = $request->home_team_id;
        $match->away_team_id = $request->away_team_id;
        $match->save();

        return redirect('/matches');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeagueMatch $leagueMatch
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $leagueMatch = LeagueMatch::find((int) $request->match);

        return view('match.show', [
            'match' => $leagueMatch
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param $match
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $match)
    {
        $leagueMatch = LeagueMatch::find($match);

        $data = [
            'leagues' => League::all(),
            'teams' => Team::all(),
            'match' => $leagueMatch
        ];

        return view('match.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\LeagueMatch $leagueMatch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeagueMatch $match)
    {
        $request->validate([
            'league_id' => 'required|exists:leagues,id',
            'match_date' => 'required|date',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id'
        ]);

        if (!$request->exists('venue_id')) {
            $request->venue_id = Team::find($request->home_team_id)->venue->id;
        }

        $match->league_id = $request->league_id;
        $match->venue_id = $request->venue_id;
        $match->match_date = $request->match_date;
        $match->home_team_id = $request->home_team_id;
        $match->away_team_id = $request->away_team_id;
        $match->save();

        return redirect($match->endpoint());
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
