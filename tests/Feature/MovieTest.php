<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_moveis()
    {
        //preparation
        Movie::create([
            'name' => "Test Movie",
            'year' => 2022,
            'runtime' => 124,
            'realeasedate' => "2020-06-12",
            'storyline' => "demo movie"
        ]);


        //action
        $response = $this->getJson('/api/movies');

        //assertion
        dd($response);
        $response->assertStatus(200);
    }
}
