<?php

namespace App\Services;

use App\Exceptions\TodolistAccessRestrictedException;
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

    public function createTodoLists(string $title): TodoList
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
    public function updateTodoLists(TodoList $todoList, string $title): TodoList
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

    private function getUser(): Authenticatable
    {
        /** @var Authenticatable $user */
        $user = $this->auth->guard()->user();
        return $user;
    }
}
