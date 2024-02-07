<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\WatchLog;
use Illuminate\Support\Carbon;



class AdminReportController extends Controller
{
    public function getReport(Request $request)
    {
        try{
            $user = $request->user();
            $is_admin= $user->hasRole('admin');
            if(!$is_admin){
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $Participants = Participant::with('user')->get();
            $today = Carbon::now()->format('Y-m-d');
            $yesterday = Carbon::yesterday()->format('Y-m-d');
            $dayBeforeYesterday = Carbon::now()->subDays(2)->format('Y-m-d');

            $Participants = $Participants->map(function ($Participant) use($dayBeforeYesterday , $yesterday, $today) {

                $doesntWatchFor48Hours="no";
                $participantCount = WatchLog::where('participant_id', $Participant->id )->count();


                $participantRegisteringDate = $Participant->created_at->format('Y-m-d');
                $dayLong = 0;
                if($Participant->status == 'finish watch' || $Participant->status == 'completed' ){
                    $finishWatchAt = $Participant->finish_watch_at;
                    $dayLong = \Carbon\Carbon::parse($Participant->finish_watch_at)->diffInDays(\Carbon\Carbon::parse($participantRegisteringDate))-$participantCount+1;
                }
                else{
                    $dayLong = \Carbon\Carbon::parse($today)->diffInDays(\Carbon\Carbon::parse($participantRegisteringDate))-$participantCount+1;
                }



                $whatchForYesterday = WatchLog::whereDate('watched_at', $yesterday)->where('participant_id', $Participant->id )->exists();
                $whatchBeforeYesterday = WatchLog::whereDate('watched_at', $dayBeforeYesterday)->where('participant_id', $Participant->id)->exists();
                $whatchforToday = WatchLog::whereDate('watched_at', $today)->where('participant_id', $Participant->id)->exists();

                if(!$whatchForYesterday && !$whatchBeforeYesterday && !$whatchforToday && $Participant->status =='in progress' )
                    $doesntWatchFor48Hours="yes";
                $status = $Participant->status;

                if($status == 'finish watch'){
                    $status = "Pending 2nd questionnaire";
                }

                $dateOfBirth = $Participant->age_group ;
                $age = Carbon::parse($dateOfBirth)->age;

                return [
                    'id' => $Participant->id,
                    'Identifier number' => $Participant->code,
                    'email' => $Participant->user->email,
                    'name' => $Participant->user->name,
                    'age' => $age ,
                    'IDAF-4C+ 1st Score' => $Participant->score,
                    'IDAF-4C+ 2nd Score' => $Participant->eventual_score,
                    'status' => $status,
                    'viewed_number' => $participantCount,
                    'is_alert' => $doesntWatchFor48Hours,
                    'number of day which the user didnt watch the video on it until finish treatement' => $dayLong


                ];
            });
            return response()->json(
                $Participants, 200);

        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }



}
