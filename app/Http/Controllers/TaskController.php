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
            'tags.*' => 'exists:tags,id', // Memastikan ID tag yang dipilih valid
            'description' => 'nullable',
            'reminder' => 'nullable',
        ], [
            'tags.required' => 'Minimal pilih 1 tag',
            'tags.min' => 'Minimal pilih 1 tag',
        ]);

        // Mengubah string 'true'/'false' dari Alpine menjadi boolean PHP murni
        $isReminder = filter_var($request->reminder, FILTER_VALIDATE_BOOLEAN);

        $task = Task::create([
            'user_id' => auth()->id() ?? 1,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'priority' => $request->priority,
            'reminder' => $isReminder,
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
            'tags.*' => 'exists:tags,id',
            'description' => 'nullable',
            'reminder' => 'nullable',
        ], [
            'tags.required' => 'Minimal pilih 1 tag',
            'tags.min' => 'Minimal pilih 1 tag',
        ]);

        // Mengubah string 'true'/'false' dari Alpine menjadi boolean PHP murni
        $isReminder = filter_var($request->reminder, FILTER_VALIDATE_BOOLEAN);

        $task->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'reminder' => $isReminder,
        ]);

        // UPDATE TAGS (Menyelaraskan ID baru dengan yang lama di pivot table)
        $task->tags()->sync($request->tags);

        return redirect()->back()->with(
            'success',
            'Task berhasil diperbarui'
        );
    }

    public function destroy(Task $task)
    {
        // Putus hubungan pivot data tag terlebih dahulu agar database tetap bersih
        $task->tags()->detach();
        $task->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function toggleStatus(Request $request, Task $task)
    {
        // Ambil input is_completed dari Alpine/Fetch API secara aman
        $task->update([
            'is_completed' => filter_var($request->input('is_completed'), FILTER_VALIDATE_BOOLEAN)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'task' => $task
        ], 200);
    }
}