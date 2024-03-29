<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Annoucement;
use App\Models\Application;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class OrganizerController extends Controller
{
    public function acceptApplication (Application $application){
        try {
            $organizer_id = Auth::user()->organizer->id;
            $organizer_announcements = Annoucement::where('organizer_id',$organizer_id)->pluck('id')->toArray();
            if(in_array($application->announcement_id , $organizer_announcements)){
                $application->confirmed_at = now();
                $application->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'application ' . $application->id . ' accepted successfully'
                ]);
            }
           else{
               return response()->json([
                   'status' => 'failed',
                   'message' => 'You cannot Make operations on applications that doesnt belong to your events '
               ]);
           }
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }
    public function rejectApplication (Application $application){
        try {
            $organizer_id = Auth::user()->organizer->id;
            $organizer_announcements = Annoucement::where('organizer_id',$organizer_id)->pluck('id')->toArray();
           if (in_array($application->announcement_id , $organizer_announcements)){
               $application->rejected_at = now();
               $application->save();
               return response()->json([
                   'status' => 'success',
                   'message' => 'application ' . $application->id . ' rejected successfully'
               ]);
           }
           else{
               return response()->json([
                   'status' => 'failed',
                   'message' => 'You cannot Make operations on applications that doesnt belong to your event '
               ]);
           }
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function allRequests()
    {
        $organizer_id = Auth::user()->organizer->id;
        $organizer_announcements = Annoucement::where('organizer_id',$organizer_id)->pluck('id')->toArray();
        $applications = Application::whereIn('announcement_id',$organizer_announcements)
            ->whereNull('confirmed_at')
            ->whereNull('rejected_at')
            ->get();

        return response()->json([
           'status' => 'success',
           'pending_applications' =>$applications
        ]);
    }

}
