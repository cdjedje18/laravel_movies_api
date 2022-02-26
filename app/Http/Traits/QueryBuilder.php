<?php

namespace App\Http\Traits;

use App\Models\Actor;
use App\Models\Movie;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait QueryBuilder
{

    private $filters = [
        "eq" => "=",
        "ne" => "!=",
        "like" => "like",
        "gt" => ">",
        "gte" => ">=",
        "lt" => "<",
        "lte" => "<="
    ];

    public function moviesQueryBuilder(Request $request)
    {
        # code...
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        // dd($request->path());

        $filterQueries = $this->getfilterQuery();

        $moviesValidFields = ['id', 'name', 'year', 'runtime', 'releasedate', 'storyline', '*'];

        $moviesFields = $this->getFields($request->fields, $moviesValidFields);

        $query = Movie::select(in_array("*", $moviesFields) ? "*" : $moviesFields);

        if ($filterQueries) {
            $whereClauses = array_map(function ($item) {
                $clause = explode(':', $item);

                if (sizeof($clause) == 3) {
                    if ($this->filters[$clause[1]] == "like") {
                        return [$clause[0], $this->filters[$clause[1]], '%' . $clause[2] . '%'];
                    }
                    return [$clause[0], $this->filters[$clause[1]], $clause[2]];
                }
            }, $filterQueries['filter']);

            // dd($whereClauses);

            $query->where($whereClauses);
        }

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


    public function actorsQueryBuilder(Request $request)
    {
        # code...
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        // dd($request->path());

        $filterQueries = $this->getfilterQuery();

        $actorsValidFields = ['id', 'name', 'birthname', 'birthdate', 'birthplace', "*"];

        $actorsFields = $this->getFields($request->fields, $actorsValidFields);

        $query = Actor::select(in_array("*", $actorsFields) ? "*" : $actorsFields);

        if ($filterQueries) {
            $whereClauses = array_map(function ($item) {
                $clause = explode(':', $item);

                if (sizeof($clause) == 3) {
                    if ($this->filters[$clause[1]] == "like") {
                        return [$clause[0], $this->filters[$clause[1]], '%' . $clause[2] . '%'];
                    }
                    return [$clause[0], $this->filters[$clause[1]], $clause[2]];
                }
            }, $filterQueries['filter']);

            // dd($whereClauses);

            $query->where($whereClauses);
        }

        if (str_contains($request->fields, "movies") || in_array("*", $actorsFields)) {

            $movieQueryFields = explode('movies', $request->fields)[1] ?? null;

            $movieQueryFields = trim($movieQueryFields, "[]") ?? null;

            // dd($actorQueryFields);
            $moviesValidFields = ['id', 'name', 'year', 'runtime', 'releasedate', 'storyline', '*'];
            $moviesFields = $this->getFields($movieQueryFields, $moviesValidFields);

            // dd($actorsFields);

            $query->with('movies:' . implode(",", $moviesFields));
        }

        return $paging ? $query->paginate($pageSize) : $query->get();
    }


    public function castsQueryBuilder(Request $request)
    {
        # code...
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        // dd($request->path());

        $filterQueries = $this->getfilterQuery();

        $castsValidFields = ['id', 'movieId', 'actorId', "*"];

        $castsFields = $this->getFields($request->fields, $castsValidFields);

        $query = Actor::select(in_array("*", $castsFields) ? "*" : $castsFields);

        if ($filterQueries) {
            $whereClauses = array_map(function ($item) {
                $clause = explode(':', $item);

                if (sizeof($clause) == 3) {
                    if ($this->filters[$clause[1]] == "like") {
                        return [$clause[0], $this->filters[$clause[1]], '%' . $clause[2] . '%'];
                    }
                    return [$clause[0], $this->filters[$clause[1]], $clause[2]];
                }
            }, $filterQueries['filter']);

            // dd($whereClauses);

            $query->where($whereClauses);
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

    public function getfilterQuery()
    {
        # code...
        $query = array_key_exists('QUERY_STRING', $_SERVER) ? explode('&', $_SERVER['QUERY_STRING']) : [];
        $params = array();

        foreach ($query as $param) {
            if (str_contains($param, "filter")) {
                list($name, $value) = explode('=', $param);
                $params[urldecode($name)][] = urldecode($value);
            }
        }

        return sizeof($params) > 0 ? $params : null;
    }
}
