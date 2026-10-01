<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::orderBy('id', 'desc')->get();
        return view('players.index', compact('players'));
    }

    public function create()
    {
        return view('players.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'group_p'     => 'required|integer',
            'status'      => 'required|in:1,2',
            'id_externo'  => 'nullable|integer',
        ]);

        Player::create($request->all());

        return redirect()
            ->route('players.index')
            ->with('success', 'Player creado correctamente');
    }

    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'group_p'     => 'required|integer',
            'status'      => 'required|in:1,2',
            'id_externo'  => 'nullable|integer',
        ]);

        $player->update($request->all());

        return redirect()
            ->route('players.index')
            ->with('success', 'Player actualizado correctamente');
    }

    public function quickUpdate(Request $request, Player $player){

    $player->update([
        'status'     => $request->status,
        'group_p'    => $request->group_p,
        'id_externo' => $request->id_externo,
    ]);

    return response()->json([
        'success' => true
    ]);
}


    public function destroy(Player $player)
    {
        $player->delete();

        return redirect()
            ->route('players.index')
            ->with('success', 'Player eliminado');
    }
}
