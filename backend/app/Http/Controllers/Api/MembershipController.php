<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    /**
     * Get memberships
     * Admin: can see all users
     * Regular user: can see only their own data
     */
    public function index(Request $request)
    {
        $currentUser = $request->user();
        
        // If regular user, return only their data
        if (!$currentUser->isAdmin()) {
            $player = Player::where('user_id', (string)$currentUser->_id)->first();
            
            $membershipData = [
                '_id' => (string)$currentUser->_id,
                'user_id' => (string)$currentUser->_id,
                'name' => $currentUser->name,
                'email' => $currentUser->email,
                'role' => $currentUser->role,
                'is_active' => $currentUser->is_active,
                'semester_memberships' => $player ? ($player->semester_memberships ?? []) : [],
                'player_id' => $player ? (string)$player->_id : null,
            ];

            return response()->json([
                'users' => [$membershipData]
            ]);
        }

        // Admin can see all users
        $users = User::all();
        $memberships = [];

        foreach ($users as $user) {
            $player = Player::where('user_id', (string)$user->_id)->first();
            
            $membershipData = [
                'user_id' => (string)$user->_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'semester_memberships' => $player ? ($player->semester_memberships ?? []) : [],
                'player_id' => $player ? (string)$player->_id : null,
            ];

            $memberships[] = $membershipData;
        }

        return response()->json([
            'memberships' => $memberships
        ]);
    }

    /**
     * Update membership status for a semester (admin only)
     */
    public function updateSemester(Request $request, $userId)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'semester' => 'required|string',
            'year' => 'required|integer',
            'status' => 'required|in:active,expired,pending',
            'renewed' => 'required|boolean',
            'renewal_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Find or create player profile
        $player = Player::where('user_id', $userId)->first();
        if (!$player) {
            $player = new Player([
                'user_id' => $userId,
                'membership_type' => 'regular',
                'is_active' => true,
                'semester_memberships' => []
            ]);
            $player->save();
        }

        // Get current semester memberships
        $semesterMemberships = $player->semester_memberships ?? [];

        // Find existing semester record or add new one
        $semesterKey = $request->semester . '_' . $request->year;
        $found = false;

        foreach ($semesterMemberships as $key => $membership) {
            $existingKey = $membership['semester'] . '_' . $membership['year'];
            if ($existingKey === $semesterKey) {
                $semesterMemberships[$key] = [
                    'semester' => $request->semester,
                    'year' => $request->year,
                    'status' => $request->status,
                    'renewed' => $request->renewed,
                    'renewal_date' => $request->renewal_date,
                    'updated_at' => now()->toISOString(),
                ];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $semesterMemberships[] = [
                'semester' => $request->semester,
                'year' => $request->year,
                'status' => $request->status,
                'renewed' => $request->renewed,
                'renewal_date' => $request->renewal_date,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ];
        }

        // Update player with new semester memberships
        $player->semester_memberships = $semesterMemberships;
        $player->save();

        return response()->json([
            'message' => 'Membership updated successfully',
            'player' => $player
        ]);
    }

    /**
     * Get available semesters
     */
    public function getSemesters(Request $request)
    {
        $currentYear = date('Y');
        $semesters = [];

        // Generate semesters starting from 2026 to next year (Semester 1 and Semester 2)
        $startYear = 2026;
        $endYear = $currentYear + 1;
        
        // Ensure we always show at least to current year + 1
        if ($endYear < $startYear) {
            $endYear = $startYear + 1;
        }
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $semesters[] = [
                'semester' => 'Semester 1',
                'year' => $year,
                'label' => "Semester 1 $year"
            ];
            $semesters[] = [
                'semester' => 'Semester 2',
                'year' => $year,
                'label' => "Semester 2 $year"
            ];
        }

        return response()->json([
            'semesters' => $semesters
        ]);
    }

    /**
     * Start a new semester (admin only)
     */
    public function startSemester(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'semester' => 'required|string',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get all users
        $users = User::all();
        $updatedCount = 0;

        foreach ($users as $user) {
            // Find or create player profile
            $player = Player::where('user_id', (string)$user->_id)->first();
            if (!$player) {
                $player = new Player([
                    'user_id' => (string)$user->_id,
                    'membership_type' => 'regular',
                    'is_active' => true,
                    'semester_memberships' => []
                ]);
            }

            // Get current semester memberships
            $semesterMemberships = $player->semester_memberships ?? [];

            // Check if this semester already exists
            $exists = false;
            foreach ($semesterMemberships as $membership) {
                if ($membership['semester'] === $request->semester && $membership['year'] === $request->year) {
                    $exists = true;
                    break;
                }
            }

            // Add new semester record if it doesn't exist
            if (!$exists) {
                $semesterMemberships[] = [
                    'semester' => $request->semester,
                    'year' => $request->year,
                    'status' => 'pending',
                    'renewed' => false,
                    'renewal_date' => null,
                    'created_at' => now()->toISOString(),
                    'updated_at' => now()->toISOString(),
                ];

                $player->semester_memberships = $semesterMemberships;
                $player->save();
                $updatedCount++;
            }
        }

        return response()->json([
            'message' => "Semester started successfully. $updatedCount users initialized.",
            'semester' => $request->semester,
            'year' => $request->year,
            'users_updated' => $updatedCount
        ]);
    }

    /**
     * End current semester and expire memberships (admin only)
     */
    public function endSemester(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'semester' => 'required|string',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get all players
        $players = Player::all();
        $updatedCount = 0;

        foreach ($players as $player) {
            $semesterMemberships = $player->semester_memberships ?? [];
            $updated = false;

            // Update the specific semester to expired
            foreach ($semesterMemberships as $key => $membership) {
                if ($membership['semester'] === $request->semester && 
                    $membership['year'] === $request->year &&
                    $membership['status'] !== 'expired') {
                    
                    $semesterMemberships[$key]['status'] = 'expired';
                    $semesterMemberships[$key]['updated_at'] = now()->toISOString();
                    $updated = true;
                }
            }

            if ($updated) {
                $player->semester_memberships = $semesterMemberships;
                $player->save();
                $updatedCount++;
            }
        }

        return response()->json([
            'message' => "Semester ended successfully. $updatedCount memberships expired.",
            'semester' => $request->semester,
            'year' => $request->year,
            'memberships_expired' => $updatedCount
        ]);
    }

    /**
     * Bulk renew memberships (admin only)
     */
    public function bulkRenew(Request $request)
    {
        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'semester' => 'required|string',
            'year' => 'required|integer',
            'user_ids' => 'required|array',
            'user_ids.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updatedCount = 0;

        foreach ($request->user_ids as $userId) {
            $player = Player::where('user_id', $userId)->first();
            if (!$player) continue;

            $semesterMemberships = $player->semester_memberships ?? [];
            $updated = false;

            // Update the specific semester
            foreach ($semesterMemberships as $key => $membership) {
                if ($membership['semester'] === $request->semester && 
                    $membership['year'] === $request->year) {
                    
                    $semesterMemberships[$key]['status'] = 'active';
                    $semesterMemberships[$key]['renewed'] = true;
                    $semesterMemberships[$key]['renewal_date'] = now()->toISOString();
                    $semesterMemberships[$key]['updated_at'] = now()->toISOString();
                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                $player->semester_memberships = $semesterMemberships;
                $player->save();
                $updatedCount++;
            }
        }

        return response()->json([
            'message' => "Bulk renewal completed. $updatedCount memberships renewed.",
            'memberships_renewed' => $updatedCount
        ]);
    }

    /**
     * Upload payment receipt for membership renewal
     */
    public function uploadPaymentReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'semester' => 'required|string',
            'year' => 'required|integer',
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Generate unique reference number
        $referenceNumber = 'GATORS-' . strtoupper(substr($user->_id, -6)) . '-' . 
                          strtoupper($request->semester[9]) . $request->year;

        // Store the file
        $file = $request->file('receipt');
        $filename = time() . '_' . $user->_id . '_' . $request->semester . '_' . $request->year . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment_receipts', $filename, 'public');

        // Get current payment receipts
        $paymentReceipts = $user->payment_receipts ?? [];

        // Add new receipt
        $newReceipt = [
            'semester' => $request->semester,
            'year' => (int)$request->year,
            'reference_number' => $referenceNumber,
            'filename' => $filename,
            'path' => $path,
            'status' => 'pending', // pending, verified, rejected
            'uploaded_at' => now()->toISOString(),
            'verified_at' => null,
            'verified_by' => null,
            'rejection_reason' => null,
        ];

        $paymentReceipts[] = $newReceipt;
        
        $user->payment_receipts = $paymentReceipts;
        $user->save();

        return response()->json([
            'message' => 'Payment receipt uploaded successfully',
            'reference_number' => $referenceNumber,
            'receipt' => $newReceipt
        ]);
    }

    /**
     * Get payment receipts for current user
     */
    public function getPaymentReceipts(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'receipts' => $user->payment_receipts ?? []
        ]);
    }

    /**
     * Get all pending payment receipts (admin only)
     */
    public function getPendingReceipts(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $users = User::all();
        $pendingReceipts = [];

        foreach ($users as $user) {
            if (!empty($user->payment_receipts)) {
                foreach ($user->payment_receipts as $receipt) {
                    if ($receipt['status'] === 'pending') {
                        $pendingReceipts[] = [
                            'user_id' => (string)$user->_id,
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                            'receipt' => $receipt
                        ];
                    }
                }
            }
        }

        return response()->json([
            'pending_receipts' => $pendingReceipts
        ]);
    }

    /**
     * Verify or reject payment receipt (admin only)
     */
    public function verifyPaymentReceipt(Request $request, $userId)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'semester' => 'required|string',
            'year' => 'required|integer',
            'action' => 'required|in:verify,reject',
            'rejection_reason' => 'required_if:action,reject|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Find the receipt
        $paymentReceipts = $user->payment_receipts ?? [];
        $receiptFound = false;

        foreach ($paymentReceipts as $key => $receipt) {
            if ($receipt['semester'] === $request->semester && 
                $receipt['year'] === (int)$request->year &&
                $receipt['status'] === 'pending') {
                
                if ($request->action === 'verify') {
                    $paymentReceipts[$key]['status'] = 'verified';
                    $paymentReceipts[$key]['verified_at'] = now()->toISOString();
                    $paymentReceipts[$key]['verified_by'] = (string)$request->user()->_id;

                    // Update semester membership status
                    $player = Player::where('user_id', $userId)->first();
                    if (!$player) {
                        $player = new Player([
                            'user_id' => $userId,
                            'membership_type' => 'regular',
                            'is_active' => true,
                            'semester_memberships' => []
                        ]);
                    }

                    $semesterMemberships = $player->semester_memberships ?? [];
                    $updated = false;

                    foreach ($semesterMemberships as $smKey => $membership) {
                        if ($membership['semester'] === $request->semester && 
                            $membership['year'] === (int)$request->year) {
                            $semesterMemberships[$smKey]['status'] = 'active';
                            $semesterMemberships[$smKey]['renewed'] = true;
                            $semesterMemberships[$smKey]['renewal_date'] = now()->toISOString();
                            $updated = true;
                            break;
                        }
                    }

                    if (!$updated) {
                        // Add new semester membership
                        $semesterMemberships[] = [
                            'semester' => $request->semester,
                            'year' => (int)$request->year,
                            'status' => 'active',
                            'renewed' => true,
                            'renewal_date' => now()->toISOString(),
                            'created_at' => now()->toISOString(),
                            'updated_at' => now()->toISOString(),
                        ];
                    }

                    $player->semester_memberships = $semesterMemberships;
                    $player->save();

                } else {
                    // Reject
                    $paymentReceipts[$key]['status'] = 'rejected';
                    $paymentReceipts[$key]['verified_at'] = now()->toISOString();
                    $paymentReceipts[$key]['verified_by'] = (string)$request->user()->_id;
                    $paymentReceipts[$key]['rejection_reason'] = $request->rejection_reason;
                }

                $receiptFound = true;
                break;
            }
        }

        if (!$receiptFound) {
            return response()->json([
                'message' => 'Payment receipt not found or already processed'
            ], 404);
        }

        $user->payment_receipts = $paymentReceipts;
        $user->save();

        return response()->json([
            'message' => $request->action === 'verify' 
                ? 'Payment verified and membership activated' 
                : 'Payment rejected',
            'receipt' => $paymentReceipts[$key]
        ]);
    }
}
