<?php

namespace App\Http\Controllers;

use App\Models\WatchLog;
use App\Http\Requests\StoreWatchLogRequest;
use App\Http\Requests\UpdateWatchLogRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;



class WatchLogController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function submitWatchingVideo(StoreWatchLogRequest $request)
    {
        $user = $request->user();
        $is_admin= $user->hasRole('admin');
        if($is_admin){
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'message' => "you are the system admin, you can't involve in treatment",
            ], 401);
        }

        $participant_id = $user->participant->id;
        $participantCount = WatchLog::where('participant_id', $participant_id )->count();
        if($participantCount>=config('dental.tratementDuration') )
        {
            return response()->json([
                'status' => false,
                'message' => "You have already finish 90 day of watching this video, please reanswere the questionnier",
            ], 401);
        }
        $currentDate = Carbon::now()->format('Y-m-d');
        $watchForToday = WatchLog::whereDate('watched_at', $currentDate)->where('participant_id', $participant_id)->exists();
        if($watchForToday)
        {
            return response()->json([
                'status' => false,
                'message' => "You have already watched today's video. We are looking forward to seeing you tomorrow.",
            ], 401);
        }
        else{
            $watchLog = WatchLog::create([
                'watched_at' => now(),
                'participant_id' => $participant_id,
            ]);
            $participantCountAfterAdd = WatchLog::where('participant_id', $participant_id )->count();

            if($participantCountAfterAdd >= config('dental.tratementDuration')){
                $user->participant()->update([
                    'status' => 'finish watch',
                    'finish_watch_at' => now()
                ]);
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => "Thanks for watching the video. you are finish now, please reanswere the questionnier",
                ], 206);

            }

            return response()->json([
                'status' => true,
                'message' => "Thanks for watching the video. We are looking forward to seeing you tomorrow.",
            ], 200);

        }
    }



}
