<?php

namespace App\Http\Controllers;

use App\Models\EventualUserAnswers;
use App\Http\Requests\StoreEventualUserAnswersRequest;
use App\Http\Requests\UpdateEventualUserAnswersRequest;
use Illuminate\Http\Request;



class EventualUserAnswersController extends Controller
{

    public function show($id , Request $request)
    {
        $user = $request->user();
        $is_admin= $user->hasRole('admin');
        if(!$is_admin){
        return response()->json(['error' => 'Unauthorized'], 401);
        }

        $answers = EventualUserAnswers::where('participant_id' , $id)->select('value' ,'question_id', 'participant_id')->with(['question' => function ($query) {
            $query->select('id', 'question');
        }])->get();

        return $answers;
    }
}
