<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    /**
     * Display a listing of sessions
     */
    public function index(Request $request)
    {
        $query = Session::with('creator', 'registrations');

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Order by date
        $query->orderBy('date', 'desc')->orderBy('start_time', 'asc');

        $sessions = $query->paginate(15);

        return response()->json($sessions);
    }

    /**
     * Store a newly created session
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:drop-in,training,tournament,special-event',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'skill_level_required' => 'nullable|in:beginner,intermediate,advanced,all',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session = Session::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'date' => $request->date,
            'start_time' => $request->date . ' ' . $request->start_time,
            'end_time' => $request->date . ' ' . $request->end_time,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'current_participants' => 0,
            'price' => $request->price,
            'skill_level_required' => $request->skill_level_required ?? 'all',
            'is_active' => true,
            'created_by' => $request->user()->_id ?? null,
        ]);

        return response()->json([
            'message' => 'Session created successfully',
            'session' => $session
        ], 201);
    }

    /**
     * Display the specified session
     */
    public function show($id)
    {
        $session = Session::with('creator', 'registrations.player.user')->find($id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        return response()->json($session);
    }

    /**
     * Update the specified session
     */
    public function update(Request $request, $id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:drop-in,training,tournament,special-event',
            'date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i',
            'location' => 'sometimes|string|max:255',
            'max_participants' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'skill_level_required' => 'nullable|in:beginner,intermediate,advanced,all',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updateData = $request->all();

        // Handle time updates
        if ($request->has('date') && $request->has('start_time')) {
            $updateData['start_time'] = $request->date . ' ' . $request->start_time;
        }

        if ($request->has('date') && $request->has('end_time')) {
            $updateData['end_time'] = $request->date . ' ' . $request->end_time;
        }

        $session->update($updateData);

        return response()->json([
            'message' => 'Session updated successfully',
            'session' => $session
        ]);
    }

    /**
     * Remove the specified session
     */
    public function destroy($id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $session->delete();

        return response()->json(['message' => 'Session deleted successfully']);
    }

    /**
     * Get upcoming sessions
     */
    public function upcoming()
    {
        $sessions = Session::where('is_active', true)
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(10)
            ->get();

        return response()->json($sessions);
    }
}
