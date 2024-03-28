<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

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
