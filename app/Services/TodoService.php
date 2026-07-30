<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DTO\TodoData;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pipeline\Pipeline;
use App\QueryFilters\Status;

final class TodoService
{

    public function get(): object
    {
        $builder = app(Pipeline::class)
            ->send(Todo::where('user_id', Auth::user()->id))
            ->through([
               Status::class,
            ])->thenReturn();

        return $builder->orderBy('id', 'desc')
            ->paginate(config('todo_settings.pagination', 10));

    }

    /**
     * @param TodoData $data
     * @return Todo
     * @throws \Throwable
     */
    public function create(TodoData $data): Todo
    {
        return $this->createOrUpdate(new Todo(), $data);
    }

    /**
     * @param Todo $todo
     * @param TodoData $data
     * @return Todo
     * @throws \Throwable
     */
    public function update(Todo $todo, TodoData $data): Todo
    {
        return $this->createOrUpdate($todo, $data);
    }

    /**
     * @param Todo $todo
     * @param TodoData $data
     * @return Todo
     * @throws \Throwable
     */
    protected function createOrUpdate(Todo $todo, TodoData $data): Todo
    {
        $todo->title       = $data->title;
        $todo->description = $data->description;
        $todo->status      = $data->status;
        $todo->user_id     = Auth::user()->id;
        $todo->save();

        return $todo;
    }

}
