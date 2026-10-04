<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectImageRequest;
use App\Http\Requests\UpdateProjectImageRequest;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ProjectImage::with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        $images = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Project images retrieved successfully.',
            'data' => $images,
        ]);
    }

    public function store(StoreProjectImageRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects/gallery', 'public');
        }

        $projectImage = ProjectImage::create($validated);
        $projectImage->load('project');

        return response()->json([
            'success' => true,
            'message' => 'Project image created successfully.',
            'data' => $projectImage,
        ], 201);
    }

    public function show(ProjectImage $projectImage): JsonResponse
    {
        $projectImage->load('project');

        return response()->json([
            'success' => true,
            'message' => 'Project image retrieved successfully.',
            'data' => $projectImage,
        ]);
    }

    public function update(UpdateProjectImageRequest $request, ProjectImage $projectImage): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($projectImage->image && Storage::disk('public')->exists($projectImage->image)) {
                Storage::disk('public')->delete($projectImage->image);
            }
            $validated['image'] = $request->file('image')->store('projects/gallery', 'public');
        }

        $projectImage->update($validated);
        $projectImage->load('project');

        return response()->json([
            'success' => true,
            'message' => 'Project image updated successfully.',
            'data' => $projectImage,
        ]);
    }

    public function destroy(ProjectImage $projectImage): JsonResponse
    {
        if ($projectImage->image && Storage::disk('public')->exists($projectImage->image)) {
            Storage::disk('public')->delete($projectImage->image);
        }

        $projectImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project image deleted successfully.',
        ]);
    }
}
