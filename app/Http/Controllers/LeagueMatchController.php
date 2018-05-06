<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeagueMatch;
use App\Http\Requests\UpdateLeagueMatch;
use App\League;
use App\LeagueMatch;
use App\Team;
use App\TeamVenue;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeagueMatchController extends Controller
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
     * @param StoreLeagueMatch $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeagueMatch $request)
    {
        if (!$request->exists('venue_id') || is_null($request->venue_id)) {
            $request->venue_id = optional(Team::find($request->home_team_id)->venue)->id;

            if (is_null($request->venue_id)) {
                $venue = new Venue();
                $venue->name = Team::find($request->home_team_id)->name . ' Venue';
                $venue->save();

                $link = new TeamVenue();
                $link->team_id = $request->home_team_id;
                $link->venue_id = $venue->id;
                $link->venue_from = Carbon::now();
                $link->save();

                $request->venue_id = $venue->id;
            }
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
     * @param UpdateLeagueMatch $request
     * @param LeagueMatch $match
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeagueMatch $request, LeagueMatch $match)
    {
        if ($request->exists('venue_id')) {
            $match->venue_id = $request->venue_id;
        }

        $match->league_id = $request->league_id;
        $match->match_date = $request->match_date;
        $match->save();

        return redirect($match->endpoint());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $match
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $match)
    {
        // Todo make safer?
        LeagueMatch::destroy($match);

        return redirect('/matches');
    }
}
