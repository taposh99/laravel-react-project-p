<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function stats()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
            'active_products' => Product::where('status', 'active')->count(),
        ];

        return response()->json($stats);
    }

    public function recentProducts()
    {
        $products = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        return response()->json($products);
    }
}