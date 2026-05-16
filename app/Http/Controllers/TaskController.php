<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
            'due_date' => 'required|date',
            'description' => 'nullable',
        ]);

        Task::create([
            'user_id' => auth()->id() ?? 1,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'priority' => $request->priority,
            'reminder' => $request->has('reminder'),
            'due_date' => $request->due_date,
            'description' => $request->description,
            'is_completed' => false,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan');
        
    }
}

