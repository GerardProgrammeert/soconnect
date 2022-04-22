<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EspressoMachine>
 */
class EspressoMachineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $waterCapacity = $this->faker->randomFloat(2, 0, 100);
        $beansCapacity = $this->faker->numberBetween(0,100);

        return [
            'water_container_level' =>  $this->faker->randomFloat(2, 0, $waterCapacity),
            'water_container_capacity' => $waterCapacity,
            'beans_container_capacity' => $beansCapacity,
            'beans_container_level' => $this->faker->numberBetween(0,$beansCapacity)
        ];

    }
}
