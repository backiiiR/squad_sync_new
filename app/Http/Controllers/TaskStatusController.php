<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class TaskStatusController extends Controller
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

        return inertia('teams/statuses/Index', [
            'team' => $team,
            'taskStatuses' => $team->taskStatuses()->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Team $team): RedirectResponse
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

        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.statuses.index', $team)
                ->with('message', 'You are not authorized to create a task status.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The task status name is required.',
            'name.string' => 'The task status name must be a string.',
            'name.max' => 'The task status name may not be greater than 255 characters.',
        ]);

        $taskStatus = new TaskStatus();
        $taskStatus->name = $request->name;
        $taskStatus->team()->associate($team);
        $taskStatus->save();

        return redirect()
            ->route('teams.statuses.index', $team)
            ->with('message', 'Task status created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskStatus $taskStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team, TaskStatus $taskStatus): RedirectResponse
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

        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.statuses.index', $team)
                ->with('message', 'You are not authorized to update this task status.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The task status name is required.',
            'name.string' => 'The task status name must be a string.',
            'name.max' => 'The task status name may not be greater than 255 characters.',
        ]);

        $taskStatus->name = $request->name;
        $taskStatus->save();

        return redirect()
            ->route('teams.statuses.index', $team)
            ->with('message', 'Task status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team, TaskStatus $taskStatus): RedirectResponse
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

        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.statuses.index', $team)
                ->with('message', 'You are not authorized to delete this task status.');
        }

        $taskStatus->delete();

        return redirect()
            ->route('teams.statuses.index', $team)
            ->with('message', 'Task status deleted successfully.');
    }
}
