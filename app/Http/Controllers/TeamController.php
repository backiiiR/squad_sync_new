<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rules;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        if (auth()->user()->is_super_admin) {
            $teams = Team::all();
        } else {
            $teams = Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->get();
        }

        return Inertia::render('teams/Index', [
            'teams' => $teams,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('teams/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to create a team.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The team name is required.',
            'name.string' => 'The team name must be a string.',
            'name.max' => 'The team name may not be greater than 255 characters.',
        ]);

        $team = new Team();
        $team->name = $request->name;
        $team->save();
        $team->users()->attach(auth()->user(), ['role' => 'admin']);

        return redirect()
            ->route('teams.index')
            ->with('message', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team): Response
    {
        return Inertia::render('teams/Show', [
            'team' => $team->load(['users']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team): Response
    {
        return Inertia::render('teams/Form', [
            'team' => $team,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team): RedirectResponse
    {
        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to update this team.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'The team name is required.',
            'name.string' => 'The team name must be a string.',
            'name.max' => 'The team name may not be greater than 255 characters.',
        ]);

        $team->name = $request->name;
        $team->save();

        return redirect()
            ->route('teams.index')
            ->with('message', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to delete this team.');
        }

        $team->delete();

        return redirect()
            ->route('teams.index')
            ->with('message', 'Team deleted successfully.');
    }

    public function inviteUser(Request $request, Team $team): RedirectResponse
    {
        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to invite users to this team.');
        }

        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()
                ->route('teams.show', $team)
                ->with('message', 'User not found.');
        }

        $team->users()->attach($user, ['role' => 'member']);

        return redirect()
            ->route('teams.show', $team)
            ->with('message', 'User invited successfully.');
    }

    public function removeUser(Team $team, User $user): RedirectResponse
    {
        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to remove users from this team.');
        }

        if (
            Team::whereHas('users', function ($query) {
                $query->where('users.id', auth()->user()->id);
            })->doesntExist()
        ) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to remove users from this team.');
        }

        $team->users()->detach($user);

        return redirect()
            ->route('teams.show', $team)
            ->with('message', 'User removed successfully.');
    }

    public function createUser(Request $request): RedirectResponse
    {
        if (!auth()->user()->is_super_admin) {
            return redirect()
                ->route('teams.index')
                ->with('message', 'You are not authorized to create users.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('teams.index')
            ->with('message', 'User created successfully.');
    }
}
