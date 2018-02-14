<?php

namespace App\Http\Controllers;

use App\LeagueFrame;
use App\LeagueFramePlayer;
use App\LeagueMatch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeagueFrameController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param LeagueMatch $match
     * @return \Illuminate\Http\Response
     */
    public function create(LeagueMatch $match)
    {
        $data = [
            'match' => $match,
            'homePlayers' => $match->homeTeam->getCurrentRoster(),
            'awayPlayers' => $match->awayTeam->getCurrentRoster()
        ];

        return view('frame.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param LeagueMatch $match
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, LeagueMatch $match)
    {
        $request->validate([
            'frame_type' => [
                'required',
                Rule::in(['single', 'double'])
            ],
            'home_player_id' => 'required|exists:players,id',
            'away_player_id' => 'required|exists:players,id',
            'winning_team' => [
                'required',
                Rule::in(['home', 'away'])
            ]
        ]);

        // Create the frame
        $frame = new LeagueFrame();
        $frame->league_match_id = $match->id;
        $frame->frame_number = $match->getNextFrameNumber();
        $frame->doubles = $request->frame_type == 'single' ? false : true;
        $frame->save();

        foreach ($request->home_player_id as $homePlayerID) {
            $playerHome = new LeagueFramePlayer();
            $playerHome->league_frame_id = $frame->id;
            $playerHome->player_id = $homePlayerID;
            $playerHome->winner = $request->winning_team == 'home';
            $playerHome->save();
        }

        foreach ($request->away_player_id as $awayPlayerID) {
            $playerAway = new LeagueFramePlayer();
            $playerAway->league_frame_id = $frame->id;
            $playerAway->player_id = $awayPlayerID;
            $playerAway->winner = $request->winning_team == 'away';
            $playerAway->save();
        }

        return redirect($match->endpoint());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeagueFrame  $leagueFrame
     * @return \Illuminate\Http\Response
     */
    public function show(LeagueFrame $leagueFrame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param LeagueMatch $match
     * @param LeagueFrame $frame
     * @return \Illuminate\Http\Response
     */
    public function edit(LeagueMatch $match, LeagueFrame $frame)
    {
        return view('frame.edit', [
            'frame' => $frame,
            'match' => $match,
            'homePlayers' => $match->homeTeam->getCurrentRoster(),
            'awayPlayers' => $match->awayTeam->getCurrentRoster()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param LeagueMatch $match
     * @param  \App\LeagueFrame $frame
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeagueMatch $match, LeagueFrame $frame)
    {
        $request->validate([
            'frame_type' => [
                'required',
                Rule::in(['single', 'double'])
            ],
            'home_player_id' => 'required|exists:players,id',
            'away_player_id' => 'required|exists:players,id',
            'winning_team' => [
                'required',
                Rule::in(['home', 'away'])
            ]
        ]);

        // Create the frame
        $frame->doubles = $request->frame_type == 'single' ? false : true;
        $frame->save();

        $frame->players->each->delete();

        foreach ($request->home_player_id as $homePlayerID) {
            $playerHome = new LeagueFramePlayer();
            $playerHome->league_frame_id = $frame->id;
            $playerHome->player_id = $homePlayerID;
            $playerHome->winner = $request->winning_team == 'home';
            $playerHome->save();
        }

        foreach ($request->away_player_id as $awayPlayerID) {
            $playerAway = new LeagueFramePlayer();
            $playerAway->league_frame_id = $frame->id;
            $playerAway->player_id = $awayPlayerID;
            $playerAway->winner = $request->winning_team == 'away';
            $playerAway->save();
        }

        return redirect($match->endpoint());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeagueFrame  $leagueFrame
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeagueFrame $leagueFrame)
    {
        //
    }
}
