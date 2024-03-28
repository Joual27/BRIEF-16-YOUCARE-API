<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\User;
use App\Models\Volunteer;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function organizerRegistration(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Organizer::create([
                'user_id' => $user->id
            ]);

            $token = auth()->guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Organizer account created successfully',
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 201);
        }
        catch (\Exception $e){
            return \response()->json($e->getMessage());
        }

    }

    public function volunteerRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'skills' => 'required|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Volunteer::create([
            'user_id' => $user->id,
            'skills' => json_encode($request->skills)
        ]);

        $token = auth()->guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);

        return response()->json([
            'status' => 'success',
            'message' => 'Volunteer account created successfully',
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();


        if (!$user) {
            return response()->json([
                'status' => 'FAILED',
                'message' => 'NO EXISTING USER WITH THIS EMAIL'
            ]);
        }
        else if($user->banned_at){
            return response()->json([
                'status' => 'error',
                'message' => 'SORRY U WERE BANNED ON ' . $user->banned_at,
            ]);
        }


        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'INVALID PASSWORD'
            ]);
        }

        if ($user->is_admin()) {
            Session::put('role', 'admin');
        } elseif ($user->is_organizer()) {
            Session::put('role', 'organizer');
        } elseif ($user->is_volunteer()) {
            Session::put('role', 'volunteer');
        }


        $token = auth()->guard('api')->attempt($credentials);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        Session::put('role','');
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

}
