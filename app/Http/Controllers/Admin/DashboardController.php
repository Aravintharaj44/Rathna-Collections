<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Admin dashboard with headline KPIs. Fuller reporting arrives in Phase 5.
     */
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('payment_status', 'paid')->sum('total'),
            'totalCustomers' => User::where('role', 'customer')->count(),
            'totalProducts' => Product::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'lowStockProducts' => Product::where('stock', '<=', 5)->count(),
            'recentOrders' => Order::with('user')->latest()->take(10)->get(),
        ]);
    }
}
