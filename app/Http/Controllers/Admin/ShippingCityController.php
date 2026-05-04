<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShippingCityController extends Controller
{
    public function index(): View
    {
        $cities = ShippingCity::orderBy('sort_order')->orderBy('id')->paginate(40);

        return view('admin.shipping-cities.index', compact('cities'));
    }

    public function create(): View
    {
        return view('admin.shipping-cities.edit', ['city' => new ShippingCity()]);
    }

    public function store(Request $request): RedirectResponse
    {
        ShippingCity::create($this->validated($request));

        return redirect()->route('admin.shipping-cities.index')
            ->with('success', 'Shipping location created.');
    }

    public function edit(ShippingCity $shipping_city): View
    {
        return view('admin.shipping-cities.edit', ['city' => $shipping_city]);
    }

    public function update(Request $request, ShippingCity $shipping_city): RedirectResponse
    {
        $shipping_city->update($this->validated($request, $shipping_city->id));

        return redirect()->route('admin.shipping-cities.index')
            ->with('success', 'Shipping location saved.');
    }

    public function destroy(ShippingCity $shipping_city): RedirectResponse
    {
        $shipping_city->delete();

        return redirect()->route('admin.shipping-cities.index')
            ->with('success', 'Shipping location deleted.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $slugRule = 'required|string|max:64|regex:/^[a-z0-9_\-]+$/';
        $slugRule .= $id
            ? '|unique:shipping_cities,slug,'.$id
            : '|unique:shipping_cities,slug';

        $request->validate([
            'name_en'         => 'required|string|max:200',
            'name_ar'         => 'nullable|string|max:200',
            'slug'            => $slugRule,
            'shipping_price'  => 'required|numeric|min:0',
            'currency'        => 'required|string|size:3',
            'sort_order'      => 'integer|min:0',
            'is_active'       => 'boolean',
        ]);

        return [
            'name'           => ['en' => $request->name_en, 'ar' => $request->name_ar],
            'slug'           => $request->slug ?: Str::slug($request->name_en, '_'),
            'shipping_price' => $request->shipping_price,
            'currency'       => strtoupper($request->currency),
            'sort_order'     => $request->input('sort_order', 0),
            'is_active'      => $request->boolean('is_active'),
        ];
    }
}
