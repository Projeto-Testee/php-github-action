<?php
declare (strict_types=1);

namespace Tests\Unit;


use App\Models\Todo;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class TodoControllerTest extends TestCase
{
    public function testIndex()
    {
        Event::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->get(route('todos.index') . '?status=Completed', [
            'Authentication: Bearer ' . $user->api_token,
            'X-Requested-With: XMLHttpRequest'
        ]);

        $response->assertStatus(200);
    }

    public function testCreate()
    {
        Event::fake();

        $user = User::factory()->create();
        $todo = Todo::factory()->make();

        $response = $this->actingAs($user, 'api')->post(route('todos.store'), [
            'title' => $todo->title,
            'status' => $todo->status,
            'description' => $todo->description,
            'user_id' => $user->id
        ], [
            'Authentication: Bearer ' . $user->api_token,
            'X-Requested-With: XMLHttpRequest'
        ]);

        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        Event::fake();

        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->put(route('todos.update', $todo->id), [
            'title' => $todo->title . $todo->id,
            'status' => $todo->status,
            'description' => $todo->description,
        ], [
            'Authentication: Bearer ' . $user->api_token,
            'X-Requested-With: XMLHttpRequest'
        ]);

        $response->assertStatus(200);
    }
}
