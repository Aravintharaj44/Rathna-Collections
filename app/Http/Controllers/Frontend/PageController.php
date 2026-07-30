<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Render a CMS page by slug.
     */
    public function show(Page $page): View
    {
        abort_unless($page->status, 404);

        return view('frontend.page', compact('page'));
    }

    /**
     * Newsletter subscription from the footer.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);

        Subscriber::firstOrCreate(['email' => $request->email]);

        return back()->with('success', 'Subscribed! Thanks for joining.');
    }
}
