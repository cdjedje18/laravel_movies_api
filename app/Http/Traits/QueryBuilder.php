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

        $moviesFields = $this->getMoviesFields($request->fields);

        $query = Movie::select($moviesFields);

        if (str_contains($request->fields, "actors") || in_array("*", $moviesFields)) {

            $actorQueryFields = explode('actors', $request->fields)[1] ?? null;

            $actorQueryFields = trim($actorQueryFields, "[]") ?? null;

            // dd($actorQueryFields);

            $actorsFields = $this->getActorsFields($actorQueryFields);

            // dd($actorsFields);

            $query->with('actors:' . implode(",", $actorsFields));
        }


        return $paging ? $query->paginate($pageSize) : $query->get();
    }


    public function getMoviesFields($fieldQuery)
    {
        # code...
        if (!$fieldQuery) {
            return ["id", "name"];
        }

        $validFields = ['id', 'name', 'year', 'runtime', 'releasedate', 'storyline', '*'];

        $fields = array_intersect(explode(",", $fieldQuery), $validFields);

        return sizeof($fields) == 0 ? null : $fields;
    }

    public function getActorsFields($fieldQuery)
    {
        # code...
        if (!$fieldQuery) {
            return ["id", "name"];
        }

        $validFields = ['id', 'name', 'birthname', 'birthdate', 'birthplace', "*"];

        $fields = array_intersect(explode(",", $fieldQuery), $validFields);

        return sizeof($fields) == 0 ? null : $fields;
    }
}
