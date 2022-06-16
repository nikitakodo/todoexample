<?php

namespace App\Exceptions;

class TodolistAccessRestrictedException extends \Exception
{
    public function __construct($message = "todo list access restricted", $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
