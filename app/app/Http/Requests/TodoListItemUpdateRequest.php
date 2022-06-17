<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $title
 * @property-read string|null $description
 * @property-read bool|null $is_completed
 */
class TodoListItemUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'string|required|min:3',
            'description' => 'string|nullable',
            'is_completed' => 'bool|nullable',
        ];
    }
}
