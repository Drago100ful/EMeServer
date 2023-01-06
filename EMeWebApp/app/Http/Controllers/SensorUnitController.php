<?php

namespace App\Http\Controllers;

use App\Models\SensorUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SensorUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {

        $sensors = SensorUnit::whereUserId($request->user()->id)->get()->toArray();
        foreach ($sensors as $i => $iValue) {
            $sensors[$i]['active']= false;
        }
        return Inertia::render('SensorUnits', ['SensorUnits' => $sensors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:32'],
            'type' => ['required'],
            'description' => ['max:256'],
            'long' => ['required', 'decimal:0,7'],
            'lat' => ['required', 'decimal:0,7']
        ]);

        $content = json_decode($request->getContent(), false);

        if (!trim($content->description ?? '')) {
            $content->description = null;
        }

        SensorUnit::create([
            'user_id' => $request->user()->id,
            'name' => $content->name,
            'type' => $content->type,
            'description' => $content->description,
            'long' => $content->long,
            'lat' => $content->lat
        ]);

        return Redirect::route('sensors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $unit = SensorUnit::where(
            [
                ['user_id', $request->user()->id],
                ['id', $id]
            ]
        );

        if($unit) {
            $unit->delete();
        }
    }

    public function generateKey() {
        $key = Str::random(32);

        while(SensorUnit::whereAuthKey($key)->count() > 0) {
            $key = Str::random(32);
        }

        return response()->json(['auth_key' => $key]);
    }
}
