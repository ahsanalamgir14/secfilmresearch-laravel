<?php

namespace App\Http\Controllers\Api;

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
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Carbon;
use App\Models\WatchLog;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

use App\Http\Trait\QuestionnierTrait;


class AuthController extends Controller
{
    use QuestionnierTrait;
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $questionnaire = $request->questionnaire;
            $this->allQuestionAnswered($questionnaire);
            // $score = $this->passQuesionnaire($questionnaire);
            $score = $this->quesionnaireScore($questionnaire);
            if($score < 2.5)
                throw new \Exception("sorry you didn't pass the questionnaire");

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $participantCode = str_pad((string)$user->id, 5, '0', STR_PAD_LEFT);
            $participant = Participant::create([
                'gender' => $request->gender,
                'age_group' => $request->age_group,
                'age_confirm' => $request->age_confirm,
                'english_confirm' => $request->english_confirm,
                'score' => $score,
                'code' =>  $participantCode,
                'status' =>  'in progress'
            ]);
            $user->participant()->save($participant);
            // event(new Registered($user));
            foreach ($questionnaire as $question) {
                UserAnswers::create([
                    'participant_id' => $participant->id,
                    'question_id' =>$question['question_id'],
                    // 'answer_id' =>$question['answeres_id'],
                    'value' =>$question['value']
                ]);
            }
            $user->assignRole('user');
            SendRegisteringEmail::dispatch($user);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken('authToken', ['*'], now()->addHours(6))->plainTextToken,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user role' =>$user->getRoleNames()[0],
                'token' => $user->createToken('authToken', ['*'], now()->addHours(6))->plainTextToken,

            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            // PersonalAccessToken::where('tokenable_id', $user->id)
            // ->delete();

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully.'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'The current password is incorrect.'
            ], 403);
        }
    }

    public function checkStatus(Request $request)
    {
        $user = $request->user();
        $is_admin= $user->hasRole('admin');
        if($is_admin){
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'message' => "you are the system admin, you don't have status",
            ], 401);
        }
        $participantStatus = $user->participant->status;
        $participantCount = WatchLog::where('participant_id', $user->participant->id )->count();

        return response()->json([
            'participant status' => $participantStatus,
            'watch number' => $participantCount


        ], 200);

    }
}
