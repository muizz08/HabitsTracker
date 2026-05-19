<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User; // Ditambahkan untuk pengaman check user cadangan
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
            'tags.*' => 'exists:tags,id',
            'description' => 'nullable',
            'reminder' => 'nullable',
        ]);

        $isReminder = filter_var($request->reminder, FILTER_VALIDATE_BOOLEAN);

        $userId = auth()->id() ?? \App\Models\User::first()?->id;

        $task = Task::create([
            'user_id' => $userId,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'priority' => $request->priority,
            'reminder' => $isReminder,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'is_completed' => false,
        ]);

        $task->tags()->attach($request->tags);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan');
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

        $isReminder = filter_var($request->reminder, FILTER_VALIDATE_BOOLEAN);

        // TAMBAHKAN LOGIKA INI: Format juga di bagian update agar tidak crash saat diedit
        $formattedDueDate = date('Y-m-d H:i:s', strtotime($request->due_date . ' 23:59:59'));

        $task->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'due_date' => $formattedDueDate, // <--- Gunakan variabel yang sudah diformat lengkap
            'description' => $request->description,
            'reminder' => $isReminder,
        ]);

        $task->tags()->sync($request->tags);

        return redirect()->back()->with('success', 'Task berhasil diperbarui');
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