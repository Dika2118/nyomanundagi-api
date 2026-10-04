<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHeroBannerRequest;
use App\Http\Requests\UpdateHeroBannerRequest;
use App\Models\HeroBanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroBannerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = HeroBanner::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%");
            });
        }

        $banners = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Hero banners retrieved successfully.',
            'data' => $banners,
        ]);
    }

    public function store(StoreHeroBannerRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero_banners', 'public');
        }

        $banner = HeroBanner::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hero banner created successfully.',
            'data' => $banner,
        ], 201);
    }

    public function show(HeroBanner $heroBanner): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Hero banner retrieved successfully.',
            'data' => $heroBanner,
        ]);
    }

    public function update(UpdateHeroBannerRequest $request, HeroBanner $heroBanner): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($heroBanner->image && Storage::disk('public')->exists($heroBanner->image)) {
                Storage::disk('public')->delete($heroBanner->image);
            }
            $validated['image'] = $request->file('image')->store('hero_banners', 'public');
        } elseif (!array_key_exists('image', $validated) || is_null($validated['image']) || $validated['image'] === '') {
            unset($validated['image']);
        }

        $heroBanner->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hero banner updated successfully.',
            'data' => $heroBanner,
        ]);
    }

    public function destroy(HeroBanner $heroBanner): JsonResponse
    {
        if ($heroBanner->image && Storage::disk('public')->exists($heroBanner->image)) {
            Storage::disk('public')->delete($heroBanner->image);
        }

        $heroBanner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hero banner deleted successfully.',
        ]);
    }
}
