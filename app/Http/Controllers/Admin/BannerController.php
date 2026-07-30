<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BannerController extends Controller
{
    use HandlesFileUpload;

    public function index(): View
    {
        $banners = Banner::latest()->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        $data = $this->prepare($request);
        $data['image'] = $this->storeFile($request->file('image'), 'banners');

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $data = $this->prepare($request);
        $data['image'] = $this->replaceFile($request->file('image'), 'banners', $banner->image);

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->deleteFile($banner->image);
        $banner->delete();

        return back()->with('success', 'Banner deleted.');
    }

    private function prepare(BannerRequest $request): array
    {
        $data = $request->safe()->except('image');
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
