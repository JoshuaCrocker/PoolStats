<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayer;
use App\Player;
use App\WLDStat;

class PlayerController extends Controller
{
    /**
     * TeamController constructor.
     */
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
        $data = [
            'players' => Player::all()
        ];

        return view('player.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('player.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePlayer $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlayer $request)
    {
        $player = new Player();
        $player->name = $request->name;
        $player->save();

        return redirect('/player');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\player $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        $statWLD = WLDStat::where('player_id', $player->id)->get();

        if ($statWLD->count() == 0) {
            $statWLD = null;
        } else {
            $statWLD = $statWLD->first();
        }

        $data = [
            'player' => $player,
            'stat_wld' => $statWLD
        ];

        return view('player.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\player $player
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $player)
    {
        $data = [
            'player' => $player
        ];

        return view('player.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StorePlayer $request
     * @param  \App\player $player
     * @return \Illuminate\Http\Response
     */
    public function update(StorePlayer $request, Player $player)
    {
        $player->name = $request->name;
        $player->save();

        return redirect(route('players.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\player $player
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Player $player)
    {
        $player->delete();

        return redirect(route('players.index'));
    }
}
