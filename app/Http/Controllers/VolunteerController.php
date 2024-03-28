<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    public function applyForAnnouncement(Request $request)
    {
        try {
            Application::create([
                'volunteer_id' => Auth::user()->volunteer->id,
                'announcement_id' => $request->announcement_id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Application done succesfully waiting for admin approval '
            ]);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

}
