<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of players
     */
    public function index(Request $request)
    {
        $query = Player::with('user');

        // Filter by membership type
        if ($request->has('membership_type')) {
            $query->where('membership_type', $request->membership_type);
        }

        // Filter by skill level
        if ($request->has('skill_level')) {
            $query->where('skill_level', $request->skill_level);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $players = $query->paginate(15);

        return response()->json($players);
    }

    /**
     * Store a newly created player
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,_id',
            'membership_type' => 'required|in:regular,premium,trial',
            'skill_level' => 'required|in:beginner,intermediate,advanced',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'medical_conditions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $player = Player::create([
            'user_id' => $request->user_id,
            'membership_type' => $request->membership_type,
            'membership_start_date' => now(),
            'membership_end_date' => now()->addYear(),
            'skill_level' => $request->skill_level,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'medical_conditions' => $request->medical_conditions,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Player profile created successfully',
            'player' => $player->load('user')
        ], 201);
    }

    /**
     * Display the specified player
     */
    public function show($id)
    {
        $player = Player::with('user', 'registrations.session')->find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player);
    }

    /**
     * Update the specified player
     */
    public function update(Request $request, $id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'membership_type' => 'sometimes|in:regular,premium,trial',
            'skill_level' => 'sometimes|in:beginner,intermediate,advanced',
            'emergency_contact_name' => 'sometimes|string|max:255',
            'emergency_contact_phone' => 'sometimes|string|max:20',
            'medical_conditions' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $player->update($request->all());

        return response()->json([
            'message' => 'Player updated successfully',
            'player' => $player->load('user')
        ]);
    }

    /**
     * Remove the specified player
     */
    public function destroy($id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $player->delete();

        return response()->json(['message' => 'Player deleted successfully']);
    }
}
