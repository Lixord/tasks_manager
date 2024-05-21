<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Jobs\CompleteTaskJob;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    public function store(Request $request)
    {
        $task = Task::create($request->only('title'));
        CompleteTaskJob::dispatch($task)->delay(now()->addMinutes(5));
        $tasks = Task::all();

        if ($tasks->count() >= 3) {
            $tasksToComplete = $tasks->slice(0, $tasks->count() - 2);
            foreach ($tasksToComplete as $taskToComplete) {
                $taskToComplete->is_completed = true;
                $taskToComplete->save();
            }
        }

        return response()->json(['task' => $task, 'tasks' => $tasks], 201);
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(null, 204);
    }

    public function update($id)
    {
        $task = Task::findOrFail($id);
        $task->is_completed = true;
        $task->save();
        return response()->json($task, 200);
    }

    public function complete($id)
    {
        $task = Task::findOrFail($id);
        $task->is_completed = true;
        $task->save();
        return response()->json($task, 200);
    }
}
