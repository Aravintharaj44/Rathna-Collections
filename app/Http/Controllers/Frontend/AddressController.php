<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        $addresses = $request->user()->addresses()->latest()->get();

        return view('frontend.account.addresses', compact('addresses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->handleDefault($request, $data);

        $request->user()->addresses()->create($data);

        return back()->with('success', 'Address added.');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $data = $this->validated($request);
        $this->handleDefault($request, $data);

        $address->update($data);

        return back()->with('success', 'Address updated.');
    }

    public function destroy(Request $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === $request->user()->id, 403);
        $address->delete();

        return back()->with('success', 'Address deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'type' => ['nullable', 'in:billing,shipping,both'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:12'],
            'country' => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * If this address is marked default, clear the flag on the user's others.
     */
    private function handleDefault(Request $request, array &$data): void
    {
        $data['is_default'] = $request->boolean('is_default');
        $data['country'] = $data['country'] ?? 'India';
        $data['type'] = $data['type'] ?? 'both';

        if ($data['is_default']) {
            $request->user()->addresses()->update(['is_default' => false]);
        }
    }
}
