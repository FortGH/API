<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RollDice;
use App\Models\User;
use Illuminate\Support\Facades\Gate;


class RollDiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {

        if($this->userExiste($id)){
            $user = User::find($id);
            if(Gate::allows('play_game',$user)){
                $user = auth()->user()->id;
                $games = User::find($user)->games;

                if($games->isEmpty()){
                    $games = "You have no games";
                }

                return response()->json([
                    'message' => 'return games',
                    'games' => $games,
                    'status' => 200
                ]);
            }else {
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 403
                ]);
            }
        }
        else{

            return response()->json([
                'message' => 'User not found',
                'status' => 404
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {

        if($this->userExiste($id)){
            $user = User::find($id);
            if(Gate::allows('play_game',$user)){
                $dice_1 = mt_rand(1,6);
                $dice_2 = mt_rand(1,6);

                if($dice_1 + $dice_2 == 7){
                    $result = 1;
                }else{
                    $result = 0;
                }

                $game = [
                    'user_id' => auth()->user()->id,
                    'dice_1' => $dice_1,
                    'dice_2' => $dice_2,
                    'result' => $result
                ];

                $rollDice = RollDice::create($game);

                return response()->json([
                    'message' => 'game created successfully',
                    'game' => $rollDice,
                    'status' => 200
                ]);
            }else {
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 403
                ]);
            }
        }else{

            return response()->json([
                'message' => 'User not found',
                'status' => 404
            ]);
        }
    }

   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        if($this->userExiste($id)){
            $user = User::find($id);
            if(Gate::allows('play_game',$user)){
                $user = auth()->user()->id;

                $games = RollDice::where('user_id',$user)->get();

                if(!$games->isEmpty()){
                    foreach($games as $game){
                        $game->delete();
                    }
                    return response()->json([
                        'message' => "games deleted successfully",
                        'status' => 200
                    ]);
                }else {
                    return response()->json([
                        'message' => "You have no games",
                        'status' => 200
                    ]);
                }
            }else {
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 403
                ]);
            }
        }else{

            return response()->json([
                'message' => 'User not found',
                'status' => 404
            ]);
        }
    }

    public function userExiste($id)
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->id == $id) {
                return true;
            }
        }
        return false;

    }
}
