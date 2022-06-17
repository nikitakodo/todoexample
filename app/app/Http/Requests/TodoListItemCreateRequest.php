<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read int $todo_list_id
 * @property-read string $title
 * @property-read string|null $description
 */
class TodoListItemCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'todo_list_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
