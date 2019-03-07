<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Team;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
   
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $teams = Team::where('name', 'LIKE', "%$keyword%")
                ->orWhere('address', 'LIKE', "%$keyword%")
                ->orWhere('year_founded', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $teams = Team::latest()->paginate($perPage);
        }
        $error_message=Session::get('error_message');
        if(Session::has('error_message'))
        {
            Session::forget('error_message');
        }
        return view('teams.index', compact('teams'))->with('error_message',$error_message);
    }

   
    public function create()
    {   
        if(Session::has('error_message'))
        {
            Session::forget('error_message');
        }
        return view('teams.create');
    }

    public function store(Request $request)
    {
                $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'year_founded' => 'required',
        ]);
        $requestData = $request->all();
        
        Team::create($requestData);

        return redirect('teams')->with('flash_message', 'Team added!');
    }

    
    public function show($id)
    {
        $team = Team::findOrFail($id);
        if(Session::has('error_message'))
        {
            Session::forget('error_message');
        }
        return view('show', compact('team'));
    }

   
    public function edit($id)
    {
        $team = Team::findOrFail($id);

        return view('teams.edit', compact('team'));
    }

    
    public function update(Request $request, $id)
    {
        if(Session::has('error_message'))
        {
            Session::forget('error_message');
        }
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'year_founded' => 'required',
        ]);
        $requestData = $request->all();
        
        $team = Team::findOrFail($id);
        $team->update($requestData);

        return redirect('teams')->with('flash_message', 'Team updated!');
    }

    
    public function destroy($id)
    {
         $team = Team::findOrFail($id);
        try {
             $team->delete();
            } 
        catch (\Illuminate\Database\QueryException $e) {

                if($e->getCode() == "23000"){ 
                    session(['error_message' => 'A team cannot be deleted if there are players']);
                }
    }
        return redirect('teams')->with('flash_message', 'Team deleted!');
    }

}
