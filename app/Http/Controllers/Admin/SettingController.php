<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use HandlesFileUpload;

    /**
     * The editable text settings, grouped for the tabbed form.
     */
    private array $fields = [
        'general' => ['site_name', 'contact_email', 'contact_phone', 'currency_symbol', 'address'],
        'social' => ['facebook_url', 'instagram_url', 'whatsapp_url', 'youtube_url'],
        'seo' => ['meta_title', 'meta_description', 'meta_keywords'],
        'payment' => ['shipping_charge', 'free_shipping_above', 'tax_percent'],
    ];

    public function edit(): View
    {
        $settings = Setting::pluck('value', 'key')->all();

        return view('admin.settings.edit', [
            'settings' => $settings,
            'groups' => $this->fields,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Persist every known text field.
        foreach ($this->fields as $group => $keys) {
            foreach ($keys as $key) {
                Setting::put($key, $request->input($key), $group);
            }
        }

        // Handle logo / favicon uploads.
        foreach (['logo', 'favicon'] as $imageKey) {
            if ($request->hasFile($imageKey)) {
                $this->deleteFile(Setting::get($imageKey));
                $path = $this->storeFile($request->file($imageKey), 'settings');
                Setting::put($imageKey, $path, 'general');
            }
        }

        return back()->with('success', 'Settings saved.');
    }
}
