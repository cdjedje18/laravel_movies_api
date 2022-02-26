<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers;
use App\Models\Cast;
use Illuminate\Http\Request;

class CastController extends Controller
{
    //

    use Helpers;

    public function index(Request $request)
    {
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        $casts = $this->castsQueryBuilder($request);

        if ($casts->total()) {
            $responseData = [
                "status" => 200,
                "pager" => [
                    "total" => $casts->total(),
                    "pageCount" => $casts->lastPage(),
                    "pageSize" => $casts->perPage(),
                    "page" => $casts->currentPage(),
                ],
                "casts" => $casts->items()
            ];
        } else {
            $responseData = [
                "status" => 200,
                "casts" => $casts
            ];
        }

        return response()->json($responseData, $responseData['status']);
    }

    public function store(Request $request)
    {
        $cast = $request->has('id') ? Cast::find($request->id) : new Cast();
        foreach ($request->all() as $key => $value) {
            $cast->{$key} = $value;
        }
        if (!$cast->id) {
            $cast->id = $this->idGenerator();
        }
        $result = $cast->save();
        // dd($cast);
        if ($result) {
            return response()->json(["status" => 201, "cast" => $cast], 201);
        }
        return response()->json(["status" => 500, "message" => "Internal Server Error"], 500);
    }
}
