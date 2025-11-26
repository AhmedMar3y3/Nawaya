<?php
namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Requests\Admin\User\UserFilterRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index(UserFilterRequest $request): View
    {
        $tab = $request->get('tab', 'active');

        $onlyTrashed = $tab === 'deleted';
        $users       = $this->userService->getUsersWithFilters($request, 15, $onlyTrashed);
        $stats       = $this->userService->getUserStats();
        $countries   = \App\Models\Country::get(['id', 'name']);

        return view('Admin.users.index', compact('users', 'stats', 'tab', 'countries'));
    }

    public function show($id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);

            return response()->json([
                'success'   => true,
                'modalType' => 'show',
                'user'      => [
                    'id'                         => $user->id,
                    'full_name'                  => $user->full_name,
                    'email'                      => $user->email,
                    'phone'                      => $user->phone,
                    'is_active'                  => $user->is_active,
                    'active_subscriptions_count' => $user->active_subscriptions_count ?? 0,
                    'country'                    => $user->country ? ['name' => $user->country->name] : null,
                    'subscriptions'              => $user->subscriptions->map(function ($sub) {
                        return [
                            'workshop' => $sub->workshop ? ['title' => $sub->workshop->title] : null,
                        ];
                    })->toArray(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات المستخدم',
            ], 404);
        }
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'modalType' => 'create',
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $this->userService->createUser($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المستخدم بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء المستخدم',
            ], 500);
        }
    }

    public function edit($id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);
            return response()->json([
                'success'   => true,
                'modalType' => 'edit',
                'user'      => [
                    'id'         => $user->id,
                    'full_name'  => $user->full_name,
                    'email'      => $user->email,
                    'phone'      => $user->phone,
                    'country_id' => $user->country_id,
                    'is_active'  => $user->is_active,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات المستخدم',
            ], 404);
        }
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $this->userService->updateUser($user, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المستخدم بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المستخدم',
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $this->userService->deleteUser($user);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستخدم بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المستخدم',
            ], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $this->userService->restoreUser($id);
            return response()->json([
                'success' => true,
                'message' => 'تم استعادة المستخدم بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة المستخدم',
            ], 500);
        }
    }

    public function permanentlyDelete($id): JsonResponse
    {
        try {
            $this->userService->permanentlyDeleteUser($id);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستخدم نهائياً بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المستخدم',
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $tab         = $request->get('tab', 'active');
        $onlyTrashed = $tab === 'deleted';

        return Excel::download(new UsersExport($request->only(['search', 'status']), $onlyTrashed), 'users.xlsx');
    }

    public function exportPdf(Request $request)
    {
        set_time_limit(180);
        ini_set('memory_limit', '512M');

        $tab         = $request->get('tab', 'active');
        $onlyTrashed = $tab === 'deleted';

        $users = $this->userService->getUsersForExport($request, $onlyTrashed, 1000);

        $pdf = PDF::loadView('Admin.users.exports.pdf', [
            'users' => $users,
            'tab'   => $tab,
        ]);

        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('isRemoteEnabled', false);
        $pdf->setOption('chroot', base_path());
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('defaultFont', 'DejaVu Sans');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', false);

        return $pdf->download('users.pdf');
    }
}
