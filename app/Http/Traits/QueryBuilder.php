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


    public function queryBuilder(Request $request, $model, $validFields)
    {
        # code...
        $paging = $request->has('paging') ? ($request->paging === 'false' ? false : true) : true;
        $pageSize = intval($request->pageSize ?? env('DEFAULT_PAGE_SIZE'));

        $fieldsAndRelations = $this->fieldsAndRelations($request->fields);

        $filterQueries = $this->getfilterQuery();

        $fields = $this->getFields($fieldsAndRelations['fields'], $validFields);

        $whereClauses = $this->getWhereClauses($filterQueries);

        $query = $model::select(in_array("*", $fields) ? "*" : $fields)->where($whereClauses);

        if (sizeof($fieldsAndRelations['relations']) > 0) {
            $query->with($fieldsAndRelations['relations']);
        }

        return $paging ? $query->paginate($pageSize) : $query->get();
    }


    public function getWhereClauses($filterQueries)
    {

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

            return $whereClauses;
        }

        return [];
    }

    public function getFields($fieldQuery, $validFields)
    {
        # code...
        if (!$fieldQuery) {
            return ["id", "name"];
        }

        $fields = array_intersect($fieldQuery, $validFields);

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

    public function fieldsAndRelations($fieldParam)
    {
        $nestedObjects = ['main'];
        $nestedObjectsAux = ['main' => []];

        $field = "";
        $chars = str_split($fieldParam);
        // dd($nestedObjects[sizeof($nestedObjects) - 1]);
        foreach ($chars as $char) {
            if ($char === ",") {
                if (array_key_exists($nestedObjects[sizeof($nestedObjects) - 1], $nestedObjectsAux)) {
                    if ($field !== "" && $field != null) {
                        array_push($nestedObjectsAux[$nestedObjects[sizeof($nestedObjects) - 1]], $field);
                    }
                } else {
                    $nestedObjectsAux[$nestedObjects[sizeof($nestedObjects) - 1]];
                }
                $field = "";
            } elseif ($char === "[") {
                $key = $nestedObjects[sizeof($nestedObjects) - 1] . "." . $field;
                $nestedObjectsAux[$key] = [];
                array_push($nestedObjects, $key);
                $field = "";
            } elseif ($char === "]") {
                if ($field !== "" && $field != null) {
                    array_push($nestedObjectsAux[$nestedObjects[sizeof($nestedObjects) - 1]], $field);
                }
                $field = "";
                array_pop($nestedObjects);
            } else {
                $field .= $char;
            }
        }
        if ($field !== "" && $field != null) {
            array_push($nestedObjectsAux[$nestedObjects[sizeof($nestedObjects) - 1]], $field);
        }

        // dd($nestedObjectsAux);

        $relations = [];
        foreach ($nestedObjectsAux as $key => $value) {
            if ($key !== "main") {
                $newKey = explode("main.", $key);
                array_push($relations, sizeof($value) > 0 ? $newKey[1] . ":" . implode(",", $value) : $newKey[1]);
            }
        }

        $fieldsAndRelations = [
            "fields" => $nestedObjectsAux['main'],
            "relations" => $relations,
        ];

        // dd($fieldsAndRelations);
        return $fieldsAndRelations;
    }
}
