<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        // $this->authorize('adminOnly');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'cfPassword' => 'required|same:password',
            'role_id' => 'required'

        ]);

        if ($validator->fails()) {
            return Response([
                'status' => 403,
                'massage' => 'validation failed'
            ], 403);
        }

        $req = $request->all();
        // Check if email is registered
        if (User::where('email', '=', $req['email'])->exists()) {
            return Response([
                'status' => 200,
                'massage' => 'your email address is already registered',
            ], 200);
        }

        $req['password'] = Hash::make($req['password']);
        $req['cfPassword'] = Hash::make($req['cfPassword']);

        $user = User::create($req);

        $token = $user->createToken('token')->plainTextToken;

        return response(['message' => "Register Success", "accessToken" => $token]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->all())) {
            return response([
                'status' => 403,
                'message' => 'invalid credentials'
            ], 403);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        return response([
            'message' => 'Login Done!',
            "user" => $token, 
        ]);

    }

    public function getDetail()
    {
        // check token
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user()->with('role')->get();
            return Response([
                'status' => 200,
                'massage' => 'success',
                'data' => $user
            ], 200);
        }

        return response([
            'status' => 401,
            'massage' => 'unauthorized'
        ], 401);
    }

    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();

        return response(['message' => "Successfully Logget out"]);
    }

}
