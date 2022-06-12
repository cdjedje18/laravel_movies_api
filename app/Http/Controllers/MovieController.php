<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers;
use App\Http\Traits\QueryBuilder;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    //
    use QueryBuilder, Helpers;

    public function index(Request $request)
    {

        try {

            $movie = new Movie();

            $movies = $this->queryBuilder($request, Movie::class, $movie->getFillable());

            if ($movies->total()) {
                $responseData = [
                    "status" => 200,
                    "pager" => [
                        "total" => $movies->total(),
                        "pageCount" => $movies->lastPage(),
                        "pageSize" => $movies->perPage(),
                        "page" => $movies->currentPage(),
                    ],
                    "movies" => $movies->items()
                ];
            } else {
                $responseData = [
                    "status" => 200,
                    "movies" => $movies
                ];
            }

            return response()->json($responseData, $responseData['status']);
        } catch (\Throwable $th) {
            // dd($th);
        }
    }

    public function show(Request $request, $id)
    {
        $responseData = null;
        $movies = Movie::find($id);

        return response()->json($movies, 200);
    }

    public function store(Request $request)
    {
        $movie = $request->has('id') ? Movie::find($request->id) : new Movie();
        foreach ($request->all() as $key => $value) {
            $movie->{$key} = $value;
        }
        if (!$movie->id) {
            $movie->id = $this->idGenerator();
        }
        $result = $movie->save();
        // dd($movie);
        if ($result) {
            return response()->json(["status" => 201, "movie" => $movie], 201);
        }
        return response()->json(["status" => 500, "message" => "Internal Server Error"], 500);
    }

    public function update(Request $request, Movie $movie)
    {
        $result = $movie->update($request->all());
        if ($result) {
            return response()->json(["status" => 200, "movie" => $movie], 200);
        }
        return response()->json(["status" => 500, "message" => "Internal Server Error"], 500);
    }
}
