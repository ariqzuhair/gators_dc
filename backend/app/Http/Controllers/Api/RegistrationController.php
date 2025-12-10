<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Session;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /**
     * Display a listing of registrations
     */
    public function index(Request $request)
    {
        $query = Registration::with('player.user', 'session');

        // Filter by player
        if ($request->has('player_id')) {
            $query->where('player_id', $request->player_id);
        }

        // Filter by session
        if ($request->has('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->orderBy('registration_date', 'desc')->paginate(15);

        return response()->json($registrations);
    }

    /**
     * Store a newly created registration
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|exists:players,_id',
            'session_id' => 'required|exists:sessions,_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if player already registered for this session
        $existingRegistration = Registration::where('player_id', $request->player_id)
            ->where('session_id', $request->session_id)
            ->where('status', 'registered')
            ->first();

        if ($existingRegistration) {
            return response()->json(['message' => 'Player already registered for this session'], 409);
        }

        // Check session availability
        $session = Session::find($request->session_id);

        if (!$session->isRegistrationOpen()) {
            return response()->json(['message' => 'Registration is closed for this session'], 400);
        }

        // Check player has active membership
        $player = Player::find($request->player_id);

        if (!$player->hasActiveMembership()) {
            return response()->json(['message' => 'Player does not have an active membership'], 403);
        }

        // Create registration
        $registration = Registration::create([
            'player_id' => $request->player_id,
            'session_id' => $request->session_id,
            'status' => 'registered',
            'payment_status' => 'pending',
            'payment_amount' => $session->price,
            'registration_date' => now(),
        ]);

        // Update session participant count
        $session->increment('current_participants');

        return response()->json([
            'message' => 'Registration successful',
            'registration' => $registration->load('player.user', 'session')
        ], 201);
    }

    /**
     * Display the specified registration
     */
    public function show($id)
    {
        $registration = Registration::with('player.user', 'session')->find($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return response()->json($registration);
    }

    /**
     * Update the specified registration
     */
    public function update(Request $request, $id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:registered,cancelled,completed,no-show',
            'payment_status' => 'sometimes|in:pending,paid,refunded',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $registration->update($request->all());

        return response()->json([
            'message' => 'Registration updated successfully',
            'registration' => $registration->load('player.user', 'session')
        ]);
    }

    /**
     * Cancel a registration
     */
    public function cancel($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        if (!$registration->canBeCancelled()) {
            return response()->json(['message' => 'Registration cannot be cancelled'], 400);
        }

        $registration->update([
            'status' => 'cancelled',
            'cancellation_date' => now(),
        ]);

        // Decrease session participant count
        $registration->session->decrement('current_participants');

        return response()->json([
            'message' => 'Registration cancelled successfully',
            'registration' => $registration
        ]);
    }

    /**
     * Remove the specified registration
     */
    public function destroy($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        // Decrease session participant count if status is registered
        if ($registration->status === 'registered') {
            $registration->session->decrement('current_participants');
        }

        $registration->delete();

        return response()->json(['message' => 'Registration deleted successfully']);
    }
}
