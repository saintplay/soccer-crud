<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        $teams = $player->teams()->get()->values();
        response()->json(["player" => $player, "teams" => $teams]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'position' => [Rule::in(['Defensa', 'Centrocampista', 'Delantero'])],
            'goals' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $player = new Player([
            'name' => $request->get('name'),
            'position' => $request->get('position'),
            'goals' => $request->get('goals'),
        ]);
        $player->save();
        response()->json($player);
    }

}
