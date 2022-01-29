<?php

namespace App\Http\Controllers;

use App\Http\Traits\QueryBuilder;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    //
    use QueryBuilder;

    public function index(Request $request)
    {
        # code...
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        $movies = $this->moviesQueryBuilder($request);

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
    }

    public function show(Request $request, $id)
    {
        # code...

        $responseData = null;
        $movies = Movie::find($id);

        return response()->json($movies, 200);
    }
}
