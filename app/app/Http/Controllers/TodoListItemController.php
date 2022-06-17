<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListItemCreateRequest;
use App\Http\Requests\TodoListItemListRequest;
use App\Http\Requests\TodoListItemUpdateRequest;
use App\Http\Resources\TodoListItemResource;
use App\Models\TodoListItem;
use App\Services\TodoListItemService;
use Illuminate\Http\JsonResponse;
use Throwable;

class TodoListItemController extends Controller
{
    private TodoListItemService $todoListItemService;

    /**
     * @param TodoListItemService $todoListItemService
     */
    public function __construct(TodoListItemService $todoListItemService)
    {
        $this->todoListItemService = $todoListItemService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param TodoListItemListRequest $request
     * @return JsonResponse
     */
    public function index(TodoListItemListRequest $request): JsonResponse
    {
        try {
            return response()->json(
                TodoListItemResource::collection($this->todoListItemService->getItems($request->todo_list_id))
            );
        } catch (Throwable $throwable) {
            return response()->json(['error' => true, 'message' => $throwable->getMessage()], $throwable->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TodoListItemCreateRequest $request
     * @return JsonResponse
     */
    public function store(TodoListItemCreateRequest $request): JsonResponse
    {
        try {
            return response()->json(
                TodoListItemResource::make(
                    $this->todoListItemService->createItem(
                        $request->todo_list_id,
                        $request->title,
                        $request->description
                    )
                )
            );
        } catch (Throwable $throwable) {
            return response()->json(['error' => true, 'message' => $throwable->getMessage()], $throwable->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param TodoListItem $todoListItem
     * @return JsonResponse
     */
    public function show(TodoListItem $todoListItem): JsonResponse
    {
        try {
            return response()->json(TodoListItemResource::make($todoListItem));
        } catch (Throwable $throwable) {
            return response()->json(['error' => true, 'message' => $throwable->getMessage()], $throwable->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TodoListItemUpdateRequest $request
     * @param TodoListItem $todoListItem
     * @return JsonResponse
     */
    public function update(TodoListItemUpdateRequest $request, TodoListItem $todoListItem): JsonResponse
    {
        try {
            return response()->json(
                TodoListItemResource::make(
                    $this->todoListItemService->updateItem(
                        $todoListItem,
                        $request->title,
                        $request->description,
                        $request->is_completed
                    )
                )
            );
        } catch (Throwable $throwable) {
            return response()->json(['error' => true, 'message' => $throwable->getMessage()], $throwable->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TodoListItem $todoListItem
     * @return JsonResponse
     */
    public function destroy(TodoListItem $todoListItem): JsonResponse
    {
        try {
            $this->todoListItemService->deleteItem($todoListItem);
            return response()->json();
        } catch (Throwable $throwable) {
            return response()->json(['error' => true, 'message' => $throwable->getMessage()], $throwable->getCode());
        }
    }
}
