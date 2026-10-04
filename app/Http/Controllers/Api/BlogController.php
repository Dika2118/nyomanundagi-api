<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Blog::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('all')) {
            $blogs = $query->latest('id')->get();
        } else {
            $perPage = (int) $request->input('per_page', 10);
            $blogs = $query->latest('id')->paginate($perPage);
        }

        return response()->json([
            'success' => true,
            'message' => 'Blogs retrieved successfully.',
            'data' => $blogs,
        ]);
    }

    public function store(StoreBlogRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        } elseif ($request->filled('image') && is_string($request->input('image'))) {
            $validated['image'] = $request->input('image');
        } else {
            unset($validated['image']);
        }

        if (empty($validated['slug']) && !empty($validated['title'])) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $count = 1;
            while (Blog::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        if (($validated['status'] ?? 'Published') === 'Published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog = Blog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Artikel blog berhasil ditambahkan.',
            'data' => $blog,
        ], 201);
    }

    public function show(string $idOrSlug): JsonResponse
    {
        $blog = is_numeric($idOrSlug)
            ? Blog::find($idOrSlug)
            : Blog::where('slug', $idOrSlug)->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel blog tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Blog retrieved successfully.',
            'data' => $blog,
        ]);
    }

    public function update(UpdateBlogRequest $request, string $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($blog->image && !str_starts_with($blog->image, 'http') && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        } elseif ($request->filled('image') && is_string($request->input('image'))) {
            $validated['image'] = $request->input('image');
        } elseif (!array_key_exists('image', $validated) || is_null($validated['image']) || $validated['image'] === '') {
            unset($validated['image']);
        }

        if (!empty($validated['title']) && empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $count = 1;
            while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        $blog->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Artikel blog berhasil diperbarui.',
            'data' => $blog,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image && !str_starts_with($blog->image, 'http') && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel blog berhasil dihapus.',
        ]);
    }
}
