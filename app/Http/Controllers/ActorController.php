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

        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;

        $actor = new Actor();

        $actors = $this->queryBuilder($request, Actor::class, $actor->getFillable(), $paging);

        if ($paging) {
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
        $actor = $request->has('id') ? Actor::find($request->id) : new Actor();
        foreach ($request->all() as $key => $value) {
            $actor->{$key} = $value;
        }
        if (!$actor->id) {
            $actor->id = $this->idGenerator();
        }
        $result = $actor->save();
        // dd($actor);
        if ($result) {
            return response()->json(["status" => 201, "actor" => $actor], 201);
        }
        return response()->json(["status" => 500, "message" => "Internal Server Error"], 500);
    }

    public function update(Request $request, Actor $actor)
    {
        $result = $actor->update($request->all());
        if ($result) {
            return response()->json(["status" => 200, "actor" => $actor], 200);
        }
        return response()->json(["status" => 500, "message" => "Internal Server Error"], 500);
    }
}
