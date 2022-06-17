<?php

namespace App\Services;

use App\Exceptions\TodolistAccessRestrictedException;
use App\Exceptions\TodoListNotFoundException;
use App\Models\TodoList;
use App\Models\TodoListItem;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class TodoListItemService
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
     * @throws TodolistAccessRestrictedException
     * @throws TodoListNotFoundException
     */
    public function getItems(int $todoListId): Collection
    {
        $todoList = $this->todoListService->getTodoList($todoListId);
        $this->todoListService->checkAccess($todoList);

        return $todoList->items;
    }

    /**
     * @throws TodoListNotFoundException
     * @throws TodolistAccessRestrictedException
     */
    public function createItem(int $todoListId, string $title, string $description = null): TodoListItem
    {
        $todoList = $this->todoListService->getTodoList($todoListId);
        $this->todoListService->checkAccess($todoList);
        $item = new TodoListItem();
        $item->todo_list_id = $todoList->id;
        $item->title = $title;
        $item->description = $description;

        $item->save();
        $item->refresh();

        return $item;
    }

    /**
     * @param TodoListItem $todoListItem
     * @param string|null $title
     * @param string|null $description
     * @param bool $is_completed
     * @return TodoListItem
     * @throws TodolistAccessRestrictedException
     */
    public function updateItem(
        TodoListItem $todoListItem,
        string       $title,
        string       $description = null,
        bool         $is_completed = false
    ): TodoListItem {
        $this->todoListService->checkAccess($todoListItem->todoList);
        $todoListItem->title = $title;
        $todoListItem->description = $description;
        $todoListItem->is_completed = $is_completed;
        $todoListItem->save();

        $this->actualiseListCompletion($todoListItem->todoList);

        return $todoListItem;
    }

    /**
     * @param TodoListItem $todoListItem
     * @return bool
     * @throws TodolistAccessRestrictedException
     * @throws Exception
     */
    public function deleteItem(TodoListItem $todoListItem): bool
    {
        $this->todoListService->checkAccess($todoListItem->todoList);
        return $todoListItem->delete();
    }

    private function actualiseListCompletion(TodoList $todoList): void
    {
        foreach ($todoList->items as $item) {
            if (!$item->is_completed) {
                $this->todoListService->setIsCompleted($todoList, false);
                return;
            }
        }

        $this->todoListService->setIsCompleted($todoList, true);
    }
}
