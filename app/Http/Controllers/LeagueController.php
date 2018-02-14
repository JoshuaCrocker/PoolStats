<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeague;
use App\League;

class LeagueController extends Controller
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
        $leagues = League::all();

        return view('league.index', [
            'leagues' => $leagues
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('league.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLeague $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeague $request)
    {
        $league = new League();
        $league->name = $request->name;
        $league->save();

        return redirect('/leagues');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\League $league
     * @return \Illuminate\Http\Response
     */
    public function show(League $league)
    {
        $data = [
            'league' => $league
        ];

        return view('league.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\League $league
     * @return \Illuminate\Http\Response
     */
    public function edit(League $league)
    {
        $data = [
            'league' => $league
        ];

        return view('league.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreLeague $request
     * @param  \App\League $league
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLeague $request, League $league)
    {
        $league->name = $request->name;
        $league->save();

        return redirect('/leagues');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\League $league
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(League $league)
    {
        $league->delete();

        return redirect('/league');
    }
}
