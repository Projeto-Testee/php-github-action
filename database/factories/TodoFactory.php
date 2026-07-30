<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = config('todo_settings.status_types', []);

        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text(50),
            'status' => $statuses[array_rand($statuses, 1)]
        ];
    }
}
