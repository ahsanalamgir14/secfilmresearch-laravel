<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\WatchLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\User;



class FileController extends Controller
{

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $path = Storage::putFile('files', $file);
        // $user = Auth::user();
        $user = $request->user();
        $user->files()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'File uploaded successfully!'
        ], 200);
    }

    public function download(Request $request , $id)
    {
        $user = $request->user();
        $is_admin= $user->hasRole('admin');
        if(!$is_admin){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try{
        $user = User::find($id);
        if(!$user)
        {
            return response()->json([
                'status' => false,
                'message' => "ther is no user with ID: $id !!!  "
            ], 500);
        }

        $file = $user->files;
        if(!$file)
        {
            return response()->json([
                'status' => false,
                'message' => "the user with ID: $id didnt have form !!!  "
            ], 500);
        }
        // if()
        return response()->download(storage_path('app/' . $file->path), $file->filename);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "there is an error in downloading this file"
            ], 500);
        }
    }

}
