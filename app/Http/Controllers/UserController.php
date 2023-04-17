<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RollDice;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\RollDiceController;


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

        $userExiste = new RolldiceController;
        if ($userExiste->userExiste($id)){
        
        if($id ==  auth()->user()->id){
           
            $user = User::find($id);

            $data = $request->validate(['name' => 'required','unique:users']);

            $user -> name = $data['name'];

            $user->save();

                return response()->json([
                    'message' => 'user updated',
                    'user' => $user,
                    'status' => 200
                ]);
        }else {
            return response()->json([
                'message' => 'Unauthorized'
            ]);
        }
        }else {
            return response()->json([
                'message' => 'User not found',
                'status' => 404
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
        $ranking = $this->createRanking();

        $users = array_reverse($ranking);

        $users1 = array_key_first($users); 

        $users2 = $users[$users1];
        

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
