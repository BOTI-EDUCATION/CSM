<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = $this->guard()->attempt($credentials);
        if ($token) {
            return response()->json(['status' => 'success','token'=>$token], 200)->header('Authorization', $token);
        }
        return response()->json(['error' => 'Email ou mot de passe incorrect !'], 401);
    }
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }
    public function user(Request $request)
    {
        $user = Auth::user();

        $userData = [
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'role' => $user->role->Alias,
            'img' => $user->getPicture()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $userData
        ]);
    }
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }
    
    private function guard()
    {
        return Auth::guard();
    }
}
