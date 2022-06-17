<?php

namespace App\Exceptions;

class TodoListNotFoundException extends \Exception
{
    public function __construct($message = "todo list not found", $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
