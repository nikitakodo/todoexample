<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $todo_list_id
 * @property string $title
 * @property string $description
 * @property bool $is_completed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TodoList $todoList
 */
class TodoListItem extends Model
{
    protected $fillable = [
        'todo_list_id',
        'title',
        'description',
        'is_completed',
    ];

    public function todoList(): BelongsTo
    {
        return $this->belongsTo(TodoList::class, 'todo_list_id');
    }
}
