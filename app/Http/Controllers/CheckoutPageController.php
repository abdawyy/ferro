<?php

namespace App\Http\Controllers;

use App\Models\ShippingCity;
use Illuminate\View\View;

class CheckoutPageController extends Controller
{
    public function __invoke(): View
    {
        $shippingCities = ShippingCity::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('checkout.index', compact('shippingCities'));
    }
}
