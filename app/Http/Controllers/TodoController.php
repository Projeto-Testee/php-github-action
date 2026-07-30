<?php
declare (strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TodoStoreRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as Collection;
use App\Support\ServiceAccessTrait;
use Throwable;
USE App\Http\Requests\TodoUpdateRequest;

class TodoController extends Controller
{
    use ServiceAccessTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return TodoResource::collection($this->todoService()->get());
    }

    /**
     * @param TodoStoreRequest $request
     * @return TodoResource
     * @throws Throwable
     */
    public function store(TodoStoreRequest $request): TodoResource
    {
        return new TodoResource($this->todoService()->create($request->getDto()));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return TodoResource
     * @throws Throwable
     */
    public function update(TodoUpdateRequest $request, $id): TodoResource
    {
        $todo = Todo::findOrFail($id);

        return new TodoResource($this->todoService()->update($todo, $request->getDto()));
    }
}
