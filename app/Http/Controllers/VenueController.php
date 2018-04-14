<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenue;
use App\Venue;
use Illuminate\Http\Request;

/**
 * Class VenueController
 * @package App\Http\Controllers
 */
class VenueController extends Controller
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
        $venues = Venue::all();

        return view('venue.index', [
            'venues' => $venues
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('venue.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVenue $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVenue $request)
    {
        $venue = new Venue();
        $venue->name = $request->name;
        $venue->save();

        return redirect(route('venues.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        return view('venue.edit', [
            'venue' => $venue
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreVenue $request
     * @param  \App\Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function update(StoreVenue $request, Venue $venue)
    {
        $venue->name = $request->name;
        $venue->save();

        return redirect(route('venues.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venue $venue
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();

        return redirect(route('venues.index'));
    }
}
