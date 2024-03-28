<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function banUser(User $user)
    {
        try {
            $user->banned_at = now();
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'user ' . $user->name . ' banned successfully'
            ]);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
