<?php
declare (strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::factory()->create();
        $toDos = [
            'Prepare for an interview',
            'Select a task for development',
            'Brainstorm the requirements',
            'Architect a solution',
            'Setup a project',
            'Setup git repository',
            'Implement a solution for selected task',
            'Test the solution',
            'Send for review'
        ];

        foreach ($toDos as $title) {
            Todo::factory(['title' => $title, 'user_id' => $user->id])->create();
        }
    }
}
