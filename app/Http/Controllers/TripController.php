<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function index()
    {
        return Trip::all();
    }

    public function show($id)
    {
        return Trip::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'starting_point' => 'required|string|max:255',
            'ending_point' => 'required|string|max:255',
            'starting_at' => 'required|date',
            'available_places' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        // dd($request->user);

        $trip = $request->user()->trips()->create($validated);

        // $trip = Auth::user()->trips()->create($validated);

        // $trip = Trip::create([
        //     'starting_point' => $request->input('starting_point'),
        //     'ending_point' => $request->input('ending_point'),
        //     'starting_at' => $request->input('starting_at'),
        //     'available_places' => $request->input('available_places'),
        //     'price' => $request->input('price'),
        //     'user_id' => auth()->user()->id, // Associe le trajet à l'utilisateur connecté
        // ]);

        return response()->json($trip, 201);
    }

    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        if ($trip->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'starting_point' => 'sometimes|required|string|max:255',
            'ending_point' => 'sometimes|required|string|max:255',
            'starting_at' => 'sometimes|required|date',
            'available_places' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|integer|min:0',
        ]);

        $trip->update($validated);

        return response()->json($trip);
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);

        if ($trip->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $trip->delete();

        return response()->json(['message' => 'Trip deleted']);
    }
}
