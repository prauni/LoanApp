<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        //dd(111);
        //$this->middleware('auth.api', ['except'=>['login','register']]);
        //dd(22);
    }

    
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $token_validity = 24*60;
        $this->guard()->factory()->setTTL($token_validity);
        if(!$token = $this->guard()->attempt($validator->validated())){
            return response()->json(['error'=>'Unauthorized', 401]);
        }
        //dd($token);
        return $this->respondWithToken($token);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json(['message'=>'User created successfully!', 'user'=>$user]);
    }

    public function logout(){
        $this->guard()->logout();
        return response()->json(['message' => 'User logout successfully.']);
    }

    public function profile(){
        //dd(753);
        return response()->json($this->guard()->user());
    }

    public function refresh(){
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function guard(){
        return Auth::guard();
    }

    protected function respondWithToken($token){
        return response()->json([
            'token'=> $token,
            'randnumber' => rand(99,9999),
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTL()*60
        ]);
    }
}
