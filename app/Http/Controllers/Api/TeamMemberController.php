<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TeamMember::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Team members retrieved successfully.',
            'data' => $members,
        ]);
    }

    public function store(StoreTeamMemberRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team_members', 'public');
        }

        $member = TeamMember::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Team member created successfully.',
            'data' => $member,
        ], 201);
    }

    public function show(TeamMember $teamMember): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Team member retrieved successfully.',
            'data' => $teamMember,
        ]);
    }

    public function update(UpdateTeamMemberRequest $request, TeamMember $teamMember): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            if ($teamMember->photo && Storage::disk('public')->exists($teamMember->photo)) {
                Storage::disk('public')->delete($teamMember->photo);
            }
            $validated['photo'] = $request->file('photo')->store('team_members', 'public');
        } elseif (!array_key_exists('photo', $validated) || is_null($validated['photo']) || $validated['photo'] === '') {
            unset($validated['photo']);
        }

        $teamMember->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Team member updated successfully.',
            'data' => $teamMember,
        ]);
    }

    public function destroy(TeamMember $teamMember): JsonResponse
    {
        if ($teamMember->photo && Storage::disk('public')->exists($teamMember->photo)) {
            Storage::disk('public')->delete($teamMember->photo);
        }

        $teamMember->delete();

        return response()->json([
            'success' => true,
            'message' => 'Team member deleted successfully.',
        ]);
    }
}
