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
            'guest_payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
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

        // Get player and check membership status
        $player = Player::find($request->player_id);
        $hasActiveMembership = $player->hasActiveMembership();
        
        // Determine registration type and payment amount
        $registrationType = $hasActiveMembership ? 'member' : 'non-member';
        $paymentAmount = 0;
        $paymentStatus = 'paid';
        $guestPaymentReceiptPath = null;
        
        // Non-members pay RM 3 for drop-in sessions
        if (!$hasActiveMembership) {
            $paymentAmount = 3.00;
            $paymentStatus = 'pending'; // Requires admin verification
            
            // Handle payment receipt upload
            if ($request->hasFile('guest_payment_receipt')) {
                $file = $request->file('guest_payment_receipt');
                $filename = time() . '_guest_' . $player->_id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('receipts/guest_payments', $filename, 'public');
                $guestPaymentReceiptPath = $path;
            } else {
                return response()->json([
                    'message' => 'Non-members must upload a payment receipt (RM 3.00)'
                ], 400);
            }
        }
        
        // Create registration
        $registration = Registration::create([
            'player_id' => $request->player_id,
            'session_id' => $request->session_id,
            'status' => 'registered',
            'payment_status' => $paymentStatus,
            'payment_amount' => $paymentAmount,
            'registration_type' => $registrationType,
            'guest_payment_receipt' => $guestPaymentReceiptPath,
            'registration_date' => now(),
        ]);

        // Update session participant count
        $session->increment('current_participants');

        $message = $hasActiveMembership 
            ? 'Registration successful'
            : 'Registration submitted. Payment pending admin verification.';

        return response()->json([
            'message' => $message,
            'registration' => $registration->load('player.user', 'session'),
            'requires_payment_verification' => !$hasActiveMembership
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

    /**
     * Get pending guest payment verifications (admin only)
     */
    public function getPendingGuestPayments(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pendingPayments = Registration::with('player.user', 'session')
            ->where('registration_type', 'non-member')
            ->where('payment_status', 'pending')
            ->orderBy('registration_date', 'desc')
            ->get();

        return response()->json([
            'pending_payments' => $pendingPayments
        ]);
    }

    /**
     * Verify guest payment receipt (admin only)
     */
    public function verifyGuestPayment(Request $request, $registrationId)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $registration = Registration::find($registrationId);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        if (!$registration->isNonMemberRegistration()) {
            return response()->json(['message' => 'This is not a non-member registration'], 400);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->action === 'approve') {
            $registration->update([
                'payment_status' => 'paid',
                'notes' => $request->notes,
            ]);

            return response()->json([
                'message' => 'Guest payment verified successfully',
                'registration' => $registration->load('player.user', 'session')
            ]);
        } else {
            // Reject payment - cancel registration and decrease participant count
            $registration->update([
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'cancellation_date' => now(),
                'notes' => $request->notes ?? 'Payment verification rejected',
            ]);

            $registration->session->decrement('current_participants');

            return response()->json([
                'message' => 'Guest payment rejected and registration cancelled',
                'registration' => $registration
            ]);
        }
    }

    /**
     * Get guest payment statistics for encouraging membership (for players)
     */
    public function getGuestPaymentStats(Request $request, $playerId)
    {
        $player = Player::find($playerId);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        // Only allow users to see their own stats or admins
        if (!$request->user()->isAdmin() && (string)$player->user_id !== (string)$request->user()->_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $guestRegistrations = Registration::where('player_id', $playerId)
            ->where('registration_type', 'non-member')
            ->where('payment_status', 'paid')
            ->get();

        $totalSpent = $guestRegistrations->sum('payment_amount');
        $sessionCount = $guestRegistrations->count();

        return response()->json([
            'total_sessions_as_guest' => $sessionCount,
            'total_spent' => $totalSpent,
            'membership_cost' => 15.00,
            'potential_savings' => max(0, $totalSpent - 15.00),
            'sessions_until_membership_value' => max(0, 5 - $sessionCount),
            'should_encourage_membership' => $totalSpent >= 15.00 || $sessionCount >= 5,
        ]);
    }
}
