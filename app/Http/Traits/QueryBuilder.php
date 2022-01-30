<?php

namespace App\Http\Traits;

use App\Models\Movie;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait QueryBuilder
{

    public function moviesQueryBuilder(Request $request)
    {
        # code...
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        $moviesValidFields = ['id', 'name', 'year', 'runtime', 'releasedate', 'storyline', '*'];

        $moviesFields = $this->getFields($request->fields, $moviesValidFields);

        $query = Movie::select(in_array("*", $moviesFields) ? "*" : $moviesFields);

        if (str_contains($request->fields, "actors") || in_array("*", $moviesFields)) {

            $actorQueryFields = explode('actors', $request->fields)[1] ?? null;

            $actorQueryFields = trim($actorQueryFields, "[]") ?? null;

            // dd($actorQueryFields);
            $actorValidFields = ['id', 'name', 'birthname', 'birthdate', 'birthplace', "*"];
            $actorsFields = $this->getFields($actorQueryFields, $actorValidFields);

            // dd($actorsFields);

            $query->with('actors:' . implode(",", $actorsFields));
        }


        return $paging ? $query->paginate($pageSize) : $query->get();
    }


    public function getFields($fieldQuery, $validFields)
    {
        # code...
        if (!$fieldQuery) {
            return ["id", "name"];
        }

        $fields = array_intersect(explode(",", $fieldQuery), $validFields);

        return sizeof($fields) == 0 ? null : $fields;
    }
}
