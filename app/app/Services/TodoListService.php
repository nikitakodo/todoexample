<?php

namespace App\Services;

use App\Exceptions\TodolistAccessRestrictedException;
use App\Exceptions\TodoListNotFoundException;
use App\Models\TodoList;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Database\Eloquent\Collection;

class TodoListService
{
    private Factory $auth;

    /**
     * @param Factory $auth
     */
    public function __construct(Factory $auth)
    {
        $this->auth = $auth;
    }

    public function getTodoLists(): Collection
    {
        return TodoList::query()->where('user_id', $this->getUser()->getAuthIdentifier())->get();
    }

    /**
     * @throws TodoListNotFoundException
     */
    public function getTodoList(int $id): TodoList
    {
        /** @var TodoList $todoList */
        $todoList =  TodoList::query()->where('id', $id)->first();
        if (!$todoList) {
            throw new TodoListNotFoundException();
        }

        return $todoList;
    }

    public function createTodoList(string $title): TodoList
    {
        $newTodoList = new TodoList();
        $newTodoList->title = $title;
        $newTodoList->user_id = $this->getUser()->getAuthIdentifier();

        $newTodoList->save();

        return $newTodoList;
    }

    /**
     * @param TodoList $todoList
     * @param string $title
     * @return TodoList
     * @throws TodolistAccessRestrictedException
     */
    public function updateTodoList(TodoList $todoList, string $title): TodoList
    {
        $this->checkAccess($todoList);
        $todoList->title = $title;

        $todoList->save();

        return $todoList;
    }

    /**
     * @param TodoList $todoList
     * @return bool
     * @throws TodolistAccessRestrictedException
     * @throws Exception
     */
    public function deleteTodoList(TodoList $todoList): bool
    {
        $this->checkAccess($todoList);

        return $todoList->delete();
    }

    /**
     * @param TodoList $todoList
     * @return void
     * @throws TodolistAccessRestrictedException
     */
    public function checkAccess(TodoList $todoList): void
    {
        $user = $this->getUser();
        if ($todoList->user_id !== $user->getAuthIdentifier()) {
            throw new TodolistAccessRestrictedException();
        }
    }

    public function setIsCompleted(TodoList $todoList, bool $isCompleted): void
    {
        if ($todoList->is_completed !== $isCompleted) {
            $todoList->is_completed = $isCompleted;
            $todoList->save();
        }
    }

    private function getUser(): Authenticatable
    {
        /** @var Authenticatable $user */
        $user = $this->auth->guard()->user();
        return $user;
    }
}
