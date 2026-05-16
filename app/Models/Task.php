<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Tambahkan ini agar kolom bisa diisi lewat Controller
    protected $fillable = [
        'title',
        'description',
        'priority',
        'task_date',
        'is_completed',
        'category_id',
        'reminder',
    ];

    // Relasi harus berada di dalam class
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}