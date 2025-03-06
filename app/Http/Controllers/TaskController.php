<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskTimeTracking;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team): Response|ResponseFactory|RedirectResponse
    {
        if (
            Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->doesntExist()
        ) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to view this team.');
        }

        $tasks = $team->tasks()->with(['assignees', 'tags'])->get();

        return inertia('teams/tasks/Index', [
            'team' => $team->load(['taskStatuses']),
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Team $team): Response|ResponseFactory
    {
        return inertia('teams/tasks/Form', [
            'team' => $team->load(['users', 'tags', 'taskStatuses']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Team $team): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'exists:task_statuses,id',
            'due_date' => 'nullable|date',
            'time_estimation' => 'nullable|integer|min:0|max:12000',
            'assignees' => 'array',
            'assignees.*' => 'exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ], [
            'title.required' => 'The task title is required.',
            'title.string' => 'The task title must be a string.',
            'title.max' => 'The task title may not be greater than 255 characters.',
            'description.string' => 'The task description must be a string.',
            'status.exists' => 'The selected status is invalid.',
            'due_date.date' => 'The due date must be a valid date.',
            'time_estimation.integer' => 'The time estimation must be an integer.',
            'time_estimation.min' => 'The time estimation must be at least 0.',
            'time_estimation.max' => 'The time estimation may not be greater than 12000.',
            'assignees.array' => 'The assignees must be an array.',
            'assignees.*.exists' => 'One or more selected assignees are invalid.',
            'tags.array' => 'The tags must be an array.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->time_estimate = $request->time_estimation;
        $task->task_status_id = $request->status;
        $task->team_id = $team->id;
        $task->save();

        $task->assignees()->sync($request->assignees);
        $task->tags()->sync($request->tags);

        return redirect()
            ->route('teams.tasks.index', $team)
            ->with('message', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team, Task $task): Response|ResponseFactory|RedirectResponse
    {
        if (
            Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->doesntExist()
        ) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to view this team.');
        }

        return inertia('teams/tasks/Show', [
            'team' => $team->load(['taskStatuses', 'users']),
            'task' => $task->load(['assignees', 'tags']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team, Task $task): Response|ResponseFactory|RedirectResponse
    {
        if (
            Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->doesntExist()
        ) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to view this team.');
        }

        return inertia('teams/tasks/Form', [
            'team' => $team->load(['users', 'tags', 'taskStatuses']),
            'task' => $task->load(['assignees', 'tags']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team, Task $task): RedirectResponse
    {
        if (
            Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->doesntExist()
        ) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to view this team.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'exists:task_statuses,id',
            'due_date' => 'nullable|date',
            'time_estimation' => 'nullable|integer|min:0',
            'assignees' => 'array',
            'assignees.*' => 'exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ], [
            'title.required' => 'The task title is required.',
            'title.string' => 'The task title must be a string.',
            'title.max' => 'The task title may not be greater than 255 characters.',
            'description.string' => 'The task description must be a string.',
            'status.exists' => 'The selected status is invalid.',
            'due_date.date' => 'The due date must be a valid date.',
            'time_estimation.integer' => 'The time estimation must be an integer.',
            'time_estimation.min' => 'The time estimation must be at least 0.',
            'assignees.array' => 'The assignees must be an array.',
            'assignees.*.exists' => 'One or more selected assignees are invalid.',
            'tags.array' => 'The tags must be an array.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
        ]);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->time_estimate = $request->time_estimation;
        $task->task_status_id = $request->status;
        $task->save();

        $task->assignees()->sync($request->assignees);
        $task->tags()->sync($request->tags);

        return redirect()
            ->route('teams.tasks.index', $team)
            ->with('message', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team, Task $task): RedirectResponse
    {
        if (
            Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->doesntExist()
        ) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to view this team.');
        }

        $task->delete();

        return redirect()
            ->route('teams.tasks.index', $team)
            ->with('message', 'Task deleted successfully.');
    }

    public function addTimeEntry(Request $request, Team $team, Task $task): RedirectResponse
    {
        if (
            Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->doesntExist()
        ) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to view this team.');
        }

        $request->validate([
            'time_spent' => 'required|integer|min:1',
        ]);

        $tracking = new TaskTimeTracking();
        $tracking->task_id = $task->id;
        $tracking->user_id = auth()->user()->id;
        $tracking->time_spent = $request->time_spent;
        $tracking->save();

        return redirect()
            ->route('teams.tasks.show', [$team, $task])
            ->with('message', 'Time entry added successfully.');
    }
}
