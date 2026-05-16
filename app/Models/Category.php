<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Tambahkan ini agar kolom 'name' dan 'color' bisa diisi
    protected $fillable = ['name', 'color'];

    /**
     * Relasi: Satu kategori memiliki banyak tugas.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}