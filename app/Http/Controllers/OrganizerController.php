<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Application;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizerController extends Controller
{
    public function acceptApplication (Application $application){
        try {
            $application->confirmed_at = now();
            return response()->json([
                'status' => 'success',
                'message' => 'application ' . $application->id . ' accepted successfully'
            ]);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }
    public function rejectApplication (Application $application){
        try {
            $application->rejected_at = now();
            return response()->json([
                'status' => 'success',
                'message' => 'application ' . $application->id . ' rejected successfully'
            ]);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

}
