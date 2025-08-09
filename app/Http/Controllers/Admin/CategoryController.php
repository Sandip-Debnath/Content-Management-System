<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN);

        $categories = Category::with('parent')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN);

        $parents = Category::pluck('title', 'id');
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id'
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN);

        // Load child categories & related articles
        $category->load([
            'children', // for nested categories
            'articles'  // assuming Category hasMany Article
        ]);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN);

        $parents = Category::where('id', '!=', $category->id)->pluck('title', 'id');
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id'
        ]);

        $category->update($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN);

        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
