<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Models\Participant;
use App\Models\EventualUserAnswers;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Orion\Http\Requests\Request;
use Orion\Http\Resources\CollectionResource;
use Orion\Http\Resources\Resource;
use App\Http\Trait\QuestionnierTrait;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendFinishNotification;


class QuestionController extends Controller
{
    use DisableAuthorization,DisablePagination,QuestionnierTrait;

    protected $model = Question::class; // or "App\Models\Post"


    public function includes() : array
    {
        return ['answers'];
    }

    public function index(Request $request)
    {
        $this->authorize($this->resolveAbility('index'), $this->resolveResourceModelClass());

        $requestedRelations = $this->relationsResolver->requestedRelations($request);

        $query = $this->buildIndexFetchQuery($request, $requestedRelations);

        $beforeHookResult = $this->beforeIndex($request);
        if ($this->hookResponds($beforeHookResult)) {
            return $beforeHookResult;
        }

        $query->select('id','question','question_key');
        $entities = $this->runIndexFetchQuery($request, $query, $this->paginator->resolvePaginationLimit($request));

        $afterHookResult = $this->afterIndex($request, $entities);
        if ($this->hookResponds($afterHookResult)) {
            return $afterHookResult;
        }

        $this->relationsResolver->guardRelationsForCollection(
            $entities instanceof Paginator ? $entities->getCollection() : $entities,
            $requestedRelations
        );

        return $this->collectionResponse($entities);
    }


    public function answerSecondQustionnier(Request $request)
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
        $participant = $user->participant;
        if($participant->status == 'in progress')
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'message' => "you didn't complete 90 day of treatment",
            ], 401);

        if($participant->status == 'completed')
        {
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'message' => "you have already responded to the questionnaire.",
            ], 401);
        }
        DB::beginTransaction();
        try{
            $questionnaire = $request->questionnaire;
            $this->allQuestionAnswered($questionnaire);
            // $score = $this->passQuesionnaire($questionnaire);
            $score = $this->quesionnaireScore($questionnaire);
            foreach ($questionnaire as $question) {
                $questionAnswered = EventualUserAnswers::where('participant_id', $participant->id)->where('question_id' , $question['question_id'])->exists();
                if($questionAnswered)
                    throw new \Exception("you have already responded to the questionnaire.");
                EventualUserAnswers::create([
                    'participant_id' => $participant->id,
                    'question_id' =>$question['question_id'],
                    // 'answer_id' =>$question['answeres_id']
                    'value' =>$question['value'],

                ]);
            }
            $user->participant()->update([
                'eventual_score' => $score,
                'status' => 'completed',
            ]);
            $user->save();
            DB::commit();
            SendFinishNotification::dispatch($user);
            return response()->json([
                'status' => true,
                'message' => 'Thank you for completing this treatment.',
            ], 200);

        }
        catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }
}

