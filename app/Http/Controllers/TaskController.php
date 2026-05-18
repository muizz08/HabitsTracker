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

            'tags' => 'required|array|min:1',

            'description' => 'nullable',
        ], [
            'tags.required' => 'Minimal pilih 1 tag',
            'tags.min' => 'Minimal pilih 1 tag',
        ]);

        $task = Task::create([
            'user_id' => auth()->id() ?? 1,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'priority' => $request->priority,
            'reminder' => $request->has('reminder'),
            'due_date' => $request->due_date,
            'description' => $request->description,
            'is_completed' => false,
        ]);

        // SIMPAN TAGS
        $task->tags()->attach($request->tags);

        return redirect()->back()->with(
            'success',
            'Tugas berhasil ditambahkan'
        );
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
            'due_date' => 'required|date',

            'tags' => 'required|array|min:1',

            'description' => 'nullable',
        ], [
            'tags.required' => 'Minimal pilih 1 tag',
            'tags.min' => 'Minimal pilih 1 tag',
        ]);

        $task->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'reminder' => $request->has('reminder'),
        ]);

        // UPDATE TAGS
        $task->tags()->sync($request->tags);

        return redirect()->back()->with(
            'success',
            'Task berhasil diperbarui'
        );
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}