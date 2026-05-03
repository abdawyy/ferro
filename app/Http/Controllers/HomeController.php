<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredProducts = Product::visible()
            ->featured()
            ->with('category')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $comingSoonProducts = Product::comingSoon()
            ->orderBy('available_at')
            ->take(3)
            ->get();

        $stats = [
            'natural_ingredients' => 47,
            'elite_athletes'      => 1200,
            'countries'           => 18,
        ];

        return view('home', compact('featuredProducts', 'comingSoonProducts', 'stats'));
    }
}
