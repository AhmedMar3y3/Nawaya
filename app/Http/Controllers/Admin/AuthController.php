<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Auth\LoginAdminRequest;

class AuthController extends Controller
{
    public function loadLoginPage()
    {
        return view('Admin.login');
    }

    public function loginUser(LoginAdminRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
        }
        return back()->withErrors(['error' => 'خطأ في كلمة المرور او المستخدم'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginPage')->with('success', 'تم تسجيل الخروج بنجاح');
    }

   public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        $hour = now()->hour;
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'صباح الخير';
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = 'مساء الخير';
        } else {
            $greeting = 'مساء الخير';
        }
        
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::where('is_active', true)->count();
        $recentUsers = \App\Models\User::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalWorkshops = \App\Models\Workshop::count();
        $activeWorkshops = \App\Models\Workshop::where('is_active', true)->count();
        $recentWorkshops = \App\Models\Workshop::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalOrders = \App\Models\Order::count();
        $completedOrders = \App\Models\Order::where('status', \App\Enums\Order\OrderStatus::COMPLETED->value)->count();
        $pendingOrders = \App\Models\Order::where('status', \App\Enums\Order\OrderStatus::PENDING->value)->count();
        $recentOrders = \App\Models\Order::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalSubscriptions = \App\Models\Subscription::count();
        $paidSubscriptions = \App\Models\Subscription::where('status', \App\Enums\Subscription\SubscriptionStatus::PAID->value)->count();
        $recentSubscriptions = \App\Models\Subscription::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalProducts = \App\Models\Product::count();
        $recentProducts = \App\Models\Product::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalSupportMessages = \App\Models\SupportMessage::count();
        $recentSupportMessages = \App\Models\SupportMessage::where('created_at', '>=', now()->subDays(7))->count();
        
        $recentUsersList = \App\Models\User::latest()->limit(5)->get(['id', 'full_name', 'email', 'created_at']);
        $recentOrdersList = \App\Models\Order::with('user')->latest()->limit(5)->get(['id', 'user_id', 'total_price', 'status', 'created_at']);
        
        return view('Admin.dashboard', compact(
            'admin',
            'greeting',
            'totalUsers',
            'activeUsers',
            'recentUsers',
            'totalWorkshops',
            'activeWorkshops',
            'recentWorkshops',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'recentOrders',
            'totalSubscriptions',
            'paidSubscriptions',
            'recentSubscriptions',
            'totalProducts',
            'recentProducts',
            'totalSupportMessages',
            'recentSupportMessages',
            'recentUsersList',
            'recentOrdersList'
        ));
    }
}
