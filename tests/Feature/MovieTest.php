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
        Movie::factory()->create();

        //action
        $response = $this->getJson('/api/movies');

        //assertion
        // dd($response);
        $response->assertStatus(200);
    }
}
