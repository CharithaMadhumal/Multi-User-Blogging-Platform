<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function input(Request $request){

        $request->validate([

        'name'=>"required",
        'user_id'=>"required",
        'email'=>"required",
        'email_verified_at'=>"required",
        'password'=>"required",
        'avatar'=>"required",
        'bio'=>"required",
        'github_id'=>"required",
        'github_token'=>"required",
        'github_refresh_token'=>"required",
        'google_id'=>"required",
        'google_token'=>"required",
        'google_refresh_token'=>"required",

        ]);

        $password = Hash::make($request->password);

        $data = User::create([
            'name'=>$request->name,
            'user_id'=>$request->user_id,
            'email'=>$request->email,
            'email_verified_at'=>$request->email_verified_at,
            'password'=>$password,
            'avatar'=>$request->avatar,
            'bio'=>$request->bio,
            'github_id'=>$request->github_id,
            'github_token'=>$request->github_token,
            'github_refresh_token'=>$request->github_refresh_token,
            'google_id'=>$request->google_id,
            'google_token'=>$request->google_token,
            'google_refresh_token'=>$request->google_refresh_token

        ]);

        return response()->json([
            'message'=>"Successfully created user",
            'data'=>$data
        ]);
    }
}
