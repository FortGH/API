<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RollDice;
use Illuminate\Support\Facades\Gate;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $users->makeVisible('succes_rate');

        if(!$users->isEmpty()){
            return response()->json([
                'message' => "All users",
                'users' => $users,
                'status' => 200
            ]);
        }else {
            return response()->json([
                'message' => "No users",
                'status' => 200
            ]);
        }
    
    }

    public function show($id){ //esta funcion no tiene ruta asignada

        $user = User::findOrFail($id);
        if(Gate::allows('play_game', $user)){
            $user = User::find($id);
            return response()->json([
                'message' => 'user found',
                'user' => $user
            ]);
        }else {
            return response()->json([
                'message' => 'Unauthorized'
            ]);
        }
       
    }

    public function rename(Request $request, $id){

        if($id ==  auth()->user()->id){
           
            $user = User::find($id);

            if($request->name){
                $data = $request->validate(['name' => 'unique:users']);

                $user -> name = $data['name'];

                $user->save();

                    return response()->json([
                        'message' => 'user updated',
                        'user' => $user,
                        'status' => 200
                    ]);
            }else{
                return response()->json([
                    'message' => 'Input name failed'
                ]);
            }
        }else {
            return response()->json([
                'message' => 'Unauthorized'
            ]);
        }

    }

    public function createRanking()
    {
        $users = User::select('id','name')->get()->toArray();
        $i = 1;

        foreach ($users as $user) {
             $users1[$user['succes_rate']] = $user;
        }
         ksort($users1);
         foreach ($users1 as $user) {
             $users2[$i] = $user;
             $i++;
        }
        $users2 = array_reverse($users2);

        return $users2;
        
    }

    public function ranking()
    {
        $users2 = $this->createRanking();
        

        if (isset($users2)) {
            return response()->json([
                'message' => 'Ranking' ,
                'users' => $users2,
                'average success rate' => $this->averageSuccessRate().'%',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => "No users",
                'status' => 200
            ]);
        }
        
    }

    public function first()
    {
        $users = array_key_first($this->createRanking());

        $users2 = $this->createRanking()[$users];
        

        if (isset($users2)) {
            return response()->json([
                'message' => 'First Player' ,
                'user' => $users2,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => "No users",
                'status' => 200
            ]);
        }
        
    }

    public function last()
    {

        $users = array_reverse($this->createRanking());

        $users1 = array_key_first($users); 

        $users2 = $this->createRanking()[$users1];
        

        if (isset($users2)) {
            return response()->json([
                'message' => 'Last player' ,
                'user' => $users2,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => "No users",
                'status' => 200
            ]);
        }
        
    }

    public function averageSuccessRate(){

        $gamesWin = RollDice::where('result', 1)->get();
        $gamesWin = $gamesWin->count();
        $games =  RollDice::all();
        $games = $games->count();

        if($games){
        $averageSuccessRate = round(($gamesWin/$games)*100,2);
        }else{
            $averageSuccessRate = 0;
        }

        return $averageSuccessRate;

    }


}
