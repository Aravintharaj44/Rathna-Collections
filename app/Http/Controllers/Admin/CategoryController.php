<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use HandlesFileUpload;

    public function index(Request $request): View
    {
        $categories = Category::query()
            ->with('parent')
            ->when($request->q, fn ($q) => $q->where('name', 'like', "%{$request->q}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        // $parents = Category::parents()->orderBy('name')->get();
        $parents = Category::orderBy('name')->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $this->prepare($request);
        $data['image'] = $this->storeFile($request->file('image'), 'categories');

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category): View
    {
        // Prevent choosing itself as a parent.
        // $parents = Category::parents()->where('id', '!=', $category->id)->orderBy('name')->get();
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $this->prepare($request);
        $data['image'] = $this->replaceFile($request->file('image'), 'categories', $category->image);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->deleteFile($category->image);
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    /**
     * Normalise the validated input (slug + boolean toggles).
     */
    private function prepare(CategoryRequest $request): array
    {
        $data = $request->safe()->except('image');
        $data['slug'] = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
