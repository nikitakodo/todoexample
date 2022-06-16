<?php

namespace App\Http\Controllers;

use App\Exceptions\TodolistAccessRestrictedException;
use App\Http\Requests\TodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use App\Services\TodoListService;
use Illuminate\Http\JsonResponse;

class TodoListController extends Controller
{
    private TodoListService $todoListService;

    /**
     * @param TodoListService $todoListService
     */
    public function __construct(TodoListService $todoListService)
    {
        $this->todoListService = $todoListService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            TodoListResource::collection(
                $this->todoListService->getTodoLists()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TodoListRequest $request
     * @return JsonResponse
     */
    public function store(TodoListRequest $request): JsonResponse
    {
        return response()->json(
            TodoListResource::make($this->todoListService->createTodoLists($request->title))
        );
    }

    /**
     * Display the specified resource.
     *
     * @param TodoList $todoList
     * @return JsonResponse
     * @throws TodolistAccessRestrictedException
     */
    public function show(TodoList $todoList): JsonResponse
    {
        $this->todoListService->checkAccess($todoList);

        return response()->json(TodoListResource::make($todoList));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TodoListRequest $request
     * @param TodoList $todoList
     * @return JsonResponse
     * @throws TodolistAccessRestrictedException
     */
    public function update(TodoListRequest $request, TodoList $todoList): JsonResponse
    {
        return response()->json(
            TodoListResource::make($this->todoListService->updateTodoLists($todoList, $request->title))
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TodoList $todoList
     * @return JsonResponse
     * @throws TodolistAccessRestrictedException
     */
    public function destroy(TodoList $todoList): JsonResponse
    {
        $this->todoListService->deleteTodoList($todoList);
        return response()->json();
    }
}
