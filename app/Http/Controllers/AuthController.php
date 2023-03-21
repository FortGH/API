<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
{
    public function register(Request $request){

        $data = $request->validate([
            'name' => 'unique:users',
            'email' => 'unique:users|required',
           'password' => 'min:6|required'
        ]);
 
        $data ['password'] = Hash::make( $data ['password']);
       

       
        if(empty($data['name']) && User::all()->isEmpty() ){
            $data['name'] = 'Anonimo';
        }else if (empty($data['name'])){
            $lastUser = User::orderBy('id','desc')->first();
            $lastID = $lastUser->id ++;
            $lastID = strval($lastUser->id);

            $data['name'] = 'Anonimo' . $lastID;
        }

        $user = User::create($data);
        if (ucfirst($user->name) == 'Admin'){
            $user->admin = true;
        }
       
        $token = $user->createToken('token')->accessToken;
        $user->save();

        return response()->json([
            'message' => 'user created successfully',
            'user' => $user,
            'token' => $token,
            'status' => 200
        ]);
    }

    public function login(Request $request){

        $credentials = $request->validate([
            'email' => ['required'],
           'password' => ['required']
        ]);
        $user = User::where('email', $request->email)->first();

        if($user){
            if(auth()->attempt($credentials)){
                $token = $user->createToken('token')->accessToken;
                return response()->json([
                    'message' => 'user loged  successfully',
                    'token' => $token,
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'message' => 'password erroneo'
                ]);
            }
        }else{
            return response()->json([
                'message' => 'user doesnt exist'
            ]);
        }
    }

    public function logout(Request $request){

        $token = $request->user()->token();

        $token->revoke();

        return response()->json([
            'message' => 'user logout successfully',
            'status' => 200
        ]);

    }
}
