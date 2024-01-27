<?php

namespace App\Http\Trait;


use App\Models\User;
use App\Models\Participant;
use App\Models\Question;
use App\Models\UserAnswers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendRegisteringEmail;
use App\Http\Requests\StoreUserRequest;

trait QuestionnierTrait {

    public function allQuestionAnswered($questionnaire)
    {
        $sendedQuestionIds = [];
        $questionIds = [];
        foreach ($questionnaire as $question) {
            $sendedQuestionIds[] = $question['question_id'];
        }
        $isDuplicatedQuestion = count($sendedQuestionIds) !== count(array_unique($sendedQuestionIds));
        $questions = Question::all();
        foreach ($questions as $question) {
            $questionIds[] = $question->id;
        }
        $allExist = true;
        foreach ($questionIds as $number) {
            if (!in_array($number, $sendedQuestionIds)) {
                $allExist = false;
                break;
            }
        }
        $onlyExist = true;
        foreach ($sendedQuestionIds as $number) {
            if (!in_array($number, $questionIds)) {
                $onlyExist = false;
                break;
            }
        }
        if ($allExist && $onlyExist &&!$isDuplicatedQuestion) {
            return true;
        }
        else
            throw new \Exception("sorry you didn't answer all question");
    }

    public function passQuesionnaire($questionnaire){
        $questionnaireResult = 0;
        foreach ($questionnaire as $question) {
            $questions = Question::all();
            $questionsData = $questions->where('id', $question['question_id'])->first();
            if($questionsData->right_answer_id == $question['answeres_id'] )
            $questionnaireResult += $questionsData->question_mark;
        }
            return $questionnaireResult;
    }
    //this is for whighted question from 1 to 5
    public function quesionnaireScore($questionnaire){
        $questionnaireResult = 0;

        foreach ($questionnaire as $question) {
            if ($question['question_key'] >=1 && $question['question_key'] <=8 ) {
                $questionnaireResult += $question['value'];
               }
        }
        return number_format($questionnaireResult/8, 1);
    }
}
