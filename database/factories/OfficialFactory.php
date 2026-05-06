<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficialFactory extends Factory
{
    public function definition(): array
    {
        $positions = ['Kagawad'];

        $committees = [
            'Peace and Order', 'Health', 'Education', 'Infrastructure',
            'Environment', 'Livelihood', 'Transport', 'BDRRM',
        ];

        $filipinoNames = [
            'Juan Santos', 'Maria Reyes', 'Jose Cruz', 'Ana Bautista',
            'Roberto Garcia', 'Luisa Mendoza', 'Ricardo Torres', 'Carmen Flores',
            'Eduardo Gonzales', 'Gloria Ramos', 'Antonio Aquino', 'Teresita Villanueva',
        ];

        $termStart = $this->faker->dateTimeBetween('-4 years', '-1 year');
        $termEnd = $this->faker->dateTimeBetween('now', '+3 years');

        return [
            'full_name' => $this->faker->randomElement($filipinoNames),
            'position' => $this->faker->randomElement($positions),
            'committee' => $this->faker->randomElement($committees),
            'contact_number' => '09'.$this->faker->numerify('#########'),
            'term_start' => $termStart,
            'term_end' => $termEnd,
            'is_active' => $this->faker->boolean(85),
            'photo_path' => null,
        ];
    }
}
