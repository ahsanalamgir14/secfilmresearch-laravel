<?php

namespace App\Http\Controllers;

use App\Models\UserAnswers;
use Illuminate\Http\Request;

class UserAnswersController extends Controller
{
    public function show(Request $request , $id)
    {
        $user = $request->user();
        if(!$user)
            return response()->json(['error' => 'Unauthorized'], 401);

        $is_admin= $user->hasRole('admin');
        if(!$is_admin){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $answers = UserAnswers::where('participant_id' , $id)->select('value' ,'question_id', 'participant_id')->with(['question' => function ($query) {
            $query->select('id', 'question');
        }])->get();

        return $answers;
    }
}
