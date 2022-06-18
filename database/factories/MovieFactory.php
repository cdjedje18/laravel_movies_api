<?php

namespace Database\Factories;

use App\Http\Traits\Helpers;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{

    use Helpers;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        return [
            'id' => $this->idGenerator(),
            'name' => $this->faker->sentence(),
            'year' => $this->faker->year(),
            'runtime' => $this->faker->numberBetween(90, 170),
            'releasedate' => $this->faker->date(),
            'storyline' => $this->faker->sentence()
        ];
    }
}
