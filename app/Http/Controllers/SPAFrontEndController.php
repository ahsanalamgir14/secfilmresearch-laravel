<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class SPAFrontEndController extends Controller
{
    public function Index(Request $request)
    {
        return view('vue-renderer-index');
    }
}
