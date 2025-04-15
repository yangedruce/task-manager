<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {
        return Task::orderByDesc('created_at')->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'is_completed' => false,
        ]);

        return response()->json([
            'message' => 'Task added successfully',
            'task' => $task,
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required_without:is_completed|min:3',
            'is_completed' => 'sometimes|boolean',
        ]);

        $task->update([
            'title' => $request->has('title') ? $request->title : $task->title,
            'is_completed' => $request->has('is_completed') ? $request->is_completed : $task->is_completed,
        ]);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
