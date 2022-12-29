<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chirp;
use App\Models\User;

class ChirpController extends Controller
{
    public function index()
    {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);

        $request->user()->chirps()->create($validated);

        return redirect(route('chirps.index'));
    }

    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        
        return view('chirps.edit', [
            'chirp' => $chirp
        ]);
    }

    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);
        $chirp->update($validated);
        return redirect(route('chirps.index'));
    }

    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        $chirp->delete();
        return redirect(route('chirps.index'));
    }
}
