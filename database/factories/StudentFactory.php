<?php

    namespace Database\Factories;

    use Illuminate\Database\Eloquent\Factories\Factory;

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
     */
    class StudentFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition()
        {
            return [
                'Name' => $this->faker->firstName,
                'Surname' => $this->faker->lastName,
                'IdentificationNo' => $this->faker->uuid,
                'Country' => $this->faker->country,
                'DateOfBirth' => $this->faker->dateTime->format('Y-m-d'),
                'RegisteredOn' => $this->faker->dateTime->format('Y-m-d'),
            ];
        }
    }
