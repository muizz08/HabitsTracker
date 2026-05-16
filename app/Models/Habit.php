<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'icon',
        'color',
        // 'completed' bisa kamu hapus dari fillable jika pelacakan status sudah pindah ke tabel logs
    ];

    /**
     * Relasi ke model HabitLog (Satu habit punya banyak catatan log harian)
     */
    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class); 
        // Catatan: Pastikan kamu sudah membuat model bernama HabitLog.php
    }
}