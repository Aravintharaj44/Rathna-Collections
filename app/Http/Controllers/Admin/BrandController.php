<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    use HandlesFileUpload;

    public function index(Request $request): View
    {
        $brands = Brand::query()
            ->when($request->q, fn ($q) => $q->where('name', 'like', "%{$request->q}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        $data = $this->prepare($request);
        $data['logo'] = $this->storeFile($request->file('logo'), 'brands');

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $this->prepare($request);
        $data['logo'] = $this->replaceFile($request->file('logo'), 'brands', $brand->logo);

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->deleteFile($brand->logo);
        $brand->delete();

        return back()->with('success', 'Brand deleted.');
    }

    private function prepare(BrandRequest $request): array
    {
        $data = $request->safe()->except('logo');
        $data['slug'] = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
