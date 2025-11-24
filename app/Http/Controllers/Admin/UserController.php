<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;
use App\Services\Admin\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\BulkActionRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Requests\Admin\User\UserFilterRequest;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index(UserFilterRequest $request): View
    {
        $filters       = $request->validated();
        $users         = $this->userService->getUsersWithFilters($filters, 15);
        $stats         = $this->userService->getUserStats();
        $filterOptions = $this->userService->getFilterOptions();

        return view('Admin.users.index', compact('users', 'stats', 'filterOptions'));
    }

    public function show(User $user): View
    {
        $user     = $this->userService->getUserById($user->id);
        $activity = $this->userService->getUserActivity($user);
        return view('Admin.users.show', compact('user', 'activity'));
    }

    public function create(): View
    {
        $filterOptions = $this->userService->getFilterOptions();
        return view('Admin.users.create', compact('filterOptions'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->createUser($request->validated());
            return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
        } catch (\Exception) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المستخدم');
        }
    }

    public function edit(User $user): View
    {
        $user          = $this->userService->getUserById($user->id);
        $filterOptions = $this->userService->getFilterOptions();
        return view('Admin.users.edit', compact('user', 'filterOptions'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->updateUser($user, $request->validated());
            return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المستخدم');
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->userService->deleteUser($user);

            return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المستخدم');
        }
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        try {
            $this->userService->toggleUserStatus($user);
            $status = $user->is_active ? 'تم تفعيل المستخدم' : 'تم إيقاف المستخدم';
            return redirect()->back()->with('success', $status);
        } catch (\Exception) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تغيير حالة المستخدم');
        }
    }

    public function bulkAction(BulkActionRequest $request): RedirectResponse
    {
        try {
            $action  = $request->input('action');
            $userIds = $request->input('user_ids', []);

            if (empty($userIds)) {
                return redirect()
                    ->back()
                    ->with('error', 'يرجى اختيار مستخدم واحد على الأقل');
            }

            switch ($action) {
                case 'delete':
                    $count   = $this->userService->bulkDeleteUsers($userIds);
                    $message = "تم حذف {$count} مستخدم بنجاح";
                    break;

                case 'activate':
                    $count   = $this->userService->bulkToggleUserStatus($userIds, true);
                    $message = "تم تفعيل {$count} مستخدم بنجاح";
                    break;

                case 'deactivate':
                    $count   = $this->userService->bulkToggleUserStatus($userIds, false);
                    $message = "تم إيقاف {$count} مستخدم بنجاح";
                    break;

                default:
                    return redirect()->back()->with('error', 'إجراء غير صحيح');
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تنفيذ الإجراء');
        }
    }
}
