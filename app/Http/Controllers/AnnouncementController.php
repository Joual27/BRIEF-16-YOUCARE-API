<?php

namespace App\Http\Controllers;

use App\Models\Annoucement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

class AnnouncementController extends Controller
{
    public function createAnnouncement(Request $request)
    {
     try{
         $request->validate([
             'title' => 'required|string|max:255',
             'description' => 'required|string',
             'date' => 'required',
             'location' => 'required',
             'type' => 'required',
             'required_skills' => 'required|array'
         ]);

         $user = Auth::user();

         $announcement = Annoucement::create([
             'title' => $request->title,
             'description' => $request->description,
             'date' => $request->date,
             'location' => $request->location ,
             'required_skills' => json_encode($request->required_skills),
             'type' => $request->type,
             'organizer_id' => $user->organizer->id
         ]);

         return response()->json([
             'status' => 'success',
             'message' => 'Announcement Created Successfully !',
             'data' => $announcement,
             'session' => Session::get('role')
         ]);
     }
     catch(\Exception $e){
         return response()->json($e->getMessage());
     }
    }

    public function updateAnnouncement(Request $request, Annoucement $announcement)
    {
        $updateData = $request->all();
        foreach ($updateData as $key => $value){
            if($announcement->offsetExists($key)){
                $announcement->$key = $value;
            }
        }
        $announcement->save();
        return response()->json([
            'message' => 'Announcement data updated successfully',
            'updated_announcement ' => $announcement
        ]);
    }

    public function deleteAnnouncement(Request $request, Annoucement $announcement){
        $announcement->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'announcement ' . $announcement->title . ' deleted successfully'
        ]);
    }

    public function allAnnouncements()
    {
        $announcements = Annoucement::all();
        return response()->json([
            'status' => 'success',
            'announcements' => $announcements
        ],200);
    }

    public function announcementsFilter(Request $request)
    {
        try {
            $keyword = $request->keyword ;
            $announcements = DB::table('announcements')
                ->where(function ($query) use ($keyword){
                    $query->where('location',$keyword)
                        ->orWhere('type',$keyword);
                })
                ->get();
            return response()->json([
                'status' => 'success',
                'announcements' => $announcements
            ],200);
        }
        catch (\Exception $e){
            response()->json($e->getMessage());
        }

    }

}
