<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Team $team): Response|RedirectResponse
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

        return Inertia::render('teams/credentials/Index', [
            'team' => $team,
            'credentials' => $team->credentials()->select([
                'id',
                'title',
                'url',
            ])->get(),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Team $team): Response|RedirectResponse
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
                ->route('teams.credentials.index', $team)
                ->with('message', 'You are not authorized to create a credential.');
        }

        return Inertia::render('teams/credentials/Form', [
            'team' => $team
        ]);
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
                ->route('teams.credentials.index', $team)
                ->with('message', 'You are not authorized to create a credential.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $credential = new Credential();
        $credential->title = $request->title;
        $credential->url = $request->url;
        $credential->username = $request->username;
        $credential->password = Crypt::encryptString($request->password);
        $credential->description = $request->description;

        $credential->team()->associate($team);
        $credential->save();

        return redirect()
            ->route('teams.credentials.index', $team)
            ->with('message', 'Credential created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team, Credential $credential): Response|RedirectResponse
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

        $credential->password = Crypt::decryptString($credential->password);

        return Inertia::render('teams/credentials/Show', [
            'team' => $team,
            'credential' => $credential,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team, Credential $credential): Response|RedirectResponse
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
                ->route('teams.credentials.index', $team)
                ->with('message', 'You are not authorized to create a credential.');
        }

        $credential->password = Crypt::decryptString($credential->password);

        return Inertia::render('teams/credentials/Form', [
            'team' => $team,
            'credential' => $credential,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team, Credential $credential): RedirectResponse
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
                ->route('teams.credentials.index', $team)
                ->with('message', 'You are not authorized to create a credential.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $credential->title = $request->title;
        $credential->url = $request->url;
        $credential->username = $request->username;
        $credential->password = Crypt::encryptString($request->password);
        $credential->description = $request->description;

        $credential->save();

        return redirect()
            ->route('teams.credentials.index', $team)
            ->with('message', 'Credential updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credential $credential): RedirectResponse
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
                ->route('teams.credentials.index', $credential->team)
                ->with('message', 'You are not authorized to create a credential.');
        }

        $credential->delete();

        return redirect()
            ->route('teams.credentials.index', $credential->team)
            ->with('message', 'Credential deleted successfully.');
    }
}
