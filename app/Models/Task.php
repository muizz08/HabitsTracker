<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'priority',
        'reminder',
        'due_date',
        'description',
        'is_completed',
    ];

    // Relasi kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }
}
