<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'project_id',
        'task_id',
    ];

    public function replies()
    {
        return $this->hasMany(CommentReply::class);
    }
}
