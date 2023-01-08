<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SensorUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SensorDataController extends Controller
{
    public function store(Request $request)
    {
        dd(true);

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

}
