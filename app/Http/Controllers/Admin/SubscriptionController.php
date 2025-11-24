<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Filters\SubscriptionFilter;
use App\Http\Controllers\Controller;
use App\Services\Admin\SubscriptionService;
use App\Http\Requests\Admin\Subscription\StoreSubscriptionRequest;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {}

    public function index(Request $request): View
    {
        $type = $request->route()->getName() === 'admin.subscriptions.trials' ? 'trial' : 'subscription';

        $query = Subscription::with('user');

        if ($type === 'trial') {
            $query->whereNotNull('trial_starts_at');
        } else {
            $query->whereNull('trial_starts_at');
        }

        $filter        = new SubscriptionFilter($request);
        $subscriptions = $filter->apply($query)->paginate(20);

        return view('Admin.subscriptions.index', compact('subscriptions', 'type'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('Admin.subscriptions.create', compact('users'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $validated = $request->validated();

        if (! isset($validated['starts_at'])) {
            $validated['starts_at'] = now()->format('Y-m-d');
        }

        $subscription = $this->subscriptionService->createSubscription($validated);
        return redirect()->route('admin.subscriptions.show', $subscription)->with('success', 'تم إنشاء الاشتراك بنجاح');
    }

    public function show(Subscription $subscription): View
    {
        $subscription->load(['user', 'user.devices']);
        $userSubscriptions = Subscription::where('user_id', $subscription->user_id)->orderBy('created_at', 'desc')->get();
        return view('Admin.subscriptions.show', compact('subscription', 'userSubscriptions'));
    }

    public function destroy(Subscription $subscription)
    {
        $this->subscriptionService->deleteSubscription($subscription);
        return redirect()->route('admin.subscriptions.index')->with('success', 'تم حذف الاشتراك بنجاح');
    }
}
