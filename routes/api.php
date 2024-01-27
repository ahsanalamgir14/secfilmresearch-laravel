<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\CodeCheckController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\WatchLogController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\FileController;

use App\Http\Controllers\UserAnswersController;
use App\Http\Controllers\EventualUserAnswersController;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\File;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::post('/auth/password-reset', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
Route::get('/check-status',[AuthController::class, 'checkStatus'])->middleware('auth:sanctum');

Route::post('password/email',  ForgotPasswordController::class);
Route::post('password/code/check', CodeCheckController::class);
Route::post('password/reset', ResetPasswordController::class);

Route::get('questions',[QuestionController::class, 'index']);

Route::post('/watch-submit',[WatchLogController::class, 'submitWatchingVideo'])->middleware('auth:sanctum');
Route::post('/submit-questionnier',[QuestionController::class, 'answerSecondQustionnier'])->middleware('auth:sanctum');
Route::get('/admin-report',[AdminReportController::class, 'getReport'])->middleware('auth:sanctum');
Route::get('/user-answers/{id}',[UserAnswersController::class, 'show'])->middleware('auth:sanctum');
Route::get('/user-eventual-answers/{id}',[EventualUserAnswersController::class, 'show'])->middleware('auth:sanctum');

Route::post('/upload-form',[FileController::class, 'upload'])->middleware('auth:sanctum');
Route::get('/user-file-download/{id}',[FileController::class, 'download'])->middleware('auth:sanctum');
