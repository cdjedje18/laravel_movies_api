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

        return $paging ? $query->paginate($pageSize) : $query->get();
    }


    public function getMoviesFields($fieldQuery)
    {
        # code...
        return ['id', 'name'];
    }
}
