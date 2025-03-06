<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class TagController extends Controller
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

        return inertia('teams/tags/Index', [
            'team' => $team,
            'tags' => $team->tags()->orderBy('name')->get(),
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
                ->route('teams.tags.index', $team)
                ->with('message', 'You are not authorized to create a tag.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The tag name is required.',
            'name.string' => 'The tag name must be a string.',
            'name.max' => 'The tag name may not be greater than 255 characters.',
            'color.string' => 'The tag color must be a string.',
            'color.max' => 'The tag color may not be greater than 7 characters.',
        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->color = $request->color;

        $tag->team()->associate($team);
        $tag->save();

        return redirect()
            ->route('teams.tags.index', $team)
            ->with('message', 'Tag created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team, Tag $tag): RedirectResponse
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
                ->route('teams.tags.index', $team)
                ->with('message', 'You are not authorized to update this tag.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ], [
            'name.required' => 'The tag name is required.',
            'name.string' => 'The tag name must be a string.',
            'name.max' => 'The tag name may not be greater than 255 characters.',
            'color.string' => 'The tag color must be a string.',
            'color.max' => 'The tag color may not be greater than 7 characters.',
        ]);

        $tag->name = $request->name;
        $tag->color = $request->color;
        $tag->save();

        return redirect()
            ->route('teams.tags.index', $team)
            ->with('message', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team, Tag $tag): RedirectResponse
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
                ->route('teams.tags.index', $tag->team)
                ->with('message', 'You are not authorized to delete this tag.');
        }

        $tag->delete();

        return redirect()
            ->route('teams.tags.index', $tag->team)
            ->with('message', 'Tag deleted successfully.');
    }
}
