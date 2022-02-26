<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers;
use App\Http\Traits\QueryBuilder;
use App\Models\Actor;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    //
    use QueryBuilder, Helpers;

    public function index(Request $request)
    {
        # code...
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        $actors = $this->actorsQueryBuilder($request);

        if ($actors->total()) {
            $responseData = [
                "status" => 200,
                "pager" => [
                    "total" => $actors->total(),
                    "pageCount" => $actors->lastPage(),
                    "pageSize" => $actors->perPage(),
                    "page" => $actors->currentPage(),
                ],
                "actors" => $actors->items()
            ];
        } else {
            $responseData = [
                "status" => 200,
                "actors" => $actors
            ];
        }

        return response()->json($responseData, $responseData['status']);
    }

    public function show(Request $request, $id)
    {
        $responseData = null;
        $actors = Actor::find($id);

        return response()->json($actors, 200);
    }

    public function store(Request $request)
    {
        $Actor = $request->has('id') ? Actor::find($request->id) : new Actor();
        foreach ($request->all() as $key => $value) {
            $Actor->{$key} = $value;
        }
        if (!$Actor->id) {
            $Actor->id = $this->idGenerator();
        }
        $result = $Actor->save();
        // dd($Actor);
        if ($result) {
            return response()->json(["status" => 201, "Actor" => $Actor], 201);
        }
        return response()->json(["status" => 500, "message" => "Internal Server Error"], 500);
    }

    public function update(Request $request, Actor $Actor)
    {
        $result = $Actor->update($request->all());
        if ($result) {
            return response()->json(["status" => 200, "Actor" => $Actor], 200);
        }
        return response()->json(["status" => 500, "message" => "Internal Server Error"], 500);
    }
}
