<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Team;
use App\Player;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $players = Player::where('first_name', 'LIKE', "%$keyword%")
                ->orWhere('last_name', 'LIKE', "%$keyword%")
                ->orWhere('height', 'LIKE', "%$keyword%")
                ->orWhere('birth_date', 'LIKE', "%$keyword%")
                ->orWhere('team_id', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $players = Player::latest()->paginate($perPage);
        }

        return view('players.index', compact('players'));
    }

    
    public function create()
    {
        $team = Team::get()->pluck('name', 'id');
        return view('players.create', compact('team'));
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'height' => 'required',
            'birth_date' => 'required',
        ]);
        $requestData = $request->all();
        
        Player::create($requestData);

        return redirect('players')->with('flash_message', 'Player added!');
    }

    
    public function show($id)
    {
        $player = Player::findOrFail($id);

        return view('players.show', compact('player'));
    }

   
    public function edit($id)
    {
        $player = Player::findOrFail($id);
        

        return view('players.edit', compact('player'));
    }

   
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'height' => 'required',
            'birth_date' => 'required',
        ]);
        $requestData = $request->all();
        
        $player = Player::findOrFail($id);
        $player->update($requestData);

        return redirect('players')->with('flash_message', 'Player updated!');
    }

   
    public function destroy($id)
    {
        Player::destroy($id);
        return redirect('players')->with('flash_message', 'Player deleted!');
    }
}
