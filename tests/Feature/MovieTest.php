<?php

namespace Tests\Feature;

use App\Http\Traits\Helpers;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase, Helpers;

    public function test_get_moveis()
    {
        //preparation
        Movie::create([
            'id' => $this->idGenerator(),
            'name' => "Test Movie",
            'year' => 2022,
            'runtime' => 124,
            'releasedate' => "2020-06-12",
            'storyline' => "demo movie"
        ]);


        //action
        $response = $this->getJson('/api/movies');

        //assertion
        $response->assertStatus(200);
    }
}
