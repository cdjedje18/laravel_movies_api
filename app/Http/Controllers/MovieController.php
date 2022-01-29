<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        $movies = Movie::all();
        return response()->json(["status" => 200, "movies" => $movies], 200);
    }

    public function show(Request $request, $id)
    {
        # code...
        $movies = Movie::find($id);
        return response()->json(["status" => 200, "movies" => $movies], 200);
    }

}
