<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectCategoryRequest;
use App\Http\Requests\UpdateProjectCategoryRequest;
use App\Models\ProjectCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ProjectCategory::query();

        if ($request->boolean('with_projects')) {
            $query->with(['projects']);
        } else {
            $query->withCount('projects');
        }

        $categories = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Project categories retrieved successfully.',
            'data' => $categories,
        ]);
    }

    public function store(StoreProjectCategoryRequest $request): JsonResponse
    {
        $category = ProjectCategory::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project category created successfully.',
            'data' => $category,
        ], 201);
    }

    public function show(ProjectCategory $projectCategory): JsonResponse
    {
        $projectCategory->load('projects.images');

        return response()->json([
            'success' => true,
            'message' => 'Project category retrieved successfully.',
            'data' => $projectCategory,
        ]);
    }

    public function update(UpdateProjectCategoryRequest $request, ProjectCategory $projectCategory): JsonResponse
    {
        $projectCategory->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project category updated successfully.',
            'data' => $projectCategory,
        ]);
    }

    public function destroy(ProjectCategory $projectCategory): JsonResponse
    {
        $projectCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project category deleted successfully.',
        ]);
    }
}
