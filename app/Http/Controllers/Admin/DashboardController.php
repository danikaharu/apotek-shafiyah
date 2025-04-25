<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard.index', compact('orders'));
    }
}
