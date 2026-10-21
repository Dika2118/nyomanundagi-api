<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SiteStat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteStatController extends Controller
{
    /**
     * GET /api/site-stats
     * Return all stats ordered by sort_order.
     */
    public function index(): JsonResponse
    {
        $projectCount = (string) Project::count();

        // Keep DB record synchronized if it exists
        SiteStat::where('key', 'projects')->update(['value' => $projectCount]);

        $stats = SiteStat::orderBy('sort_order')->get();

        $stats->transform(function ($stat) use ($projectCount) {
            if ($stat->key === 'projects') {
                $stat->value = $projectCount;
                $stat->is_editable = false;
            } else {
                $stat->is_editable = true;
            }
            return $stat;
        });

        return response()->json([
            'success' => true,
            'message' => 'Site stats retrieved successfully.',
            'data'    => $stats,
        ]);
    }

    /**
     * POST /api/site-stats
     * Create a new stat.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key'        => ['required', 'string', 'max:100', 'unique:site_stats,key'],
            'label'      => ['required', 'string', 'max:255'],
            'value'      => ['required', 'string', 'max:255'],
            'suffix'     => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $stat = SiteStat::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Stat created successfully.',
            'data'    => $stat,
        ], 201);
    }

    /**
     * GET /api/site-stats/{siteStat}
     */
    public function show(SiteStat $siteStat): JsonResponse
    {
        if ($siteStat->key === 'projects') {
            $siteStat->value = (string) Project::count();
            $siteStat->is_editable = false;
        } else {
            $siteStat->is_editable = true;
        }

        return response()->json([
            'success' => true,
            'data'    => $siteStat,
        ]);
    }

    /**
     * PUT/PATCH /api/site-stats/{siteStat}
     */
    public function update(Request $request, SiteStat $siteStat): JsonResponse
    {
        if ($siteStat->key === 'projects') {
            return response()->json([
                'success' => false,
                'message' => 'Statistik Projects dihitung otomatis dari data proyek dan tidak dapat diedit secara manual.',
            ], 422);
        }

        $validated = $request->validate([
            'key'        => ['sometimes', 'string', 'max:100', "unique:site_stats,key,{$siteStat->id}"],
            'label'      => ['sometimes', 'string', 'max:255'],
            'value'      => ['sometimes', 'string', 'max:255'],
            'suffix'     => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $siteStat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Stat updated successfully.',
            'data'    => $siteStat,
        ]);
    }

    /**
     * DELETE /api/site-stats/{siteStat}
     */
    public function destroy(SiteStat $siteStat): JsonResponse
    {
        if ($siteStat->key === 'projects') {
            return response()->json([
                'success' => false,
                'message' => 'Statistik Projects tidak dapat dihapus karena terhubung langsung dengan sistem.',
            ], 422);
        }

        $siteStat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stat deleted successfully.',
        ]);
    }
}
