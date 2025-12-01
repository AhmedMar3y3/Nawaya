<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Filters\UserFilter;
use App\Enums\Subscription\SubscriptionStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserService
{
    public function getUsersWithFilters(Request $request, int $perPage = 15, bool $onlyTrashed = false): LengthAwarePaginator
    {
        $query = $onlyTrashed ? User::onlyTrashed() : User::query();

        $filter = new UserFilter($request);
        $query = $filter->apply($query);

        $query->withCount([
            'subscriptions as active_subscriptions_count' => function ($q) {
                $q->where('status', SubscriptionStatus::PAID->value);
            }
        ]);

        $query->latest();

        return $query->paginate($perPage);
    }

    public function getUsersForExport(Request $request, bool $onlyTrashed = false, int $limit = 1000): array
    {
        $query = $onlyTrashed ? User::onlyTrashed() : User::query();

        $filter = new UserFilter($request);
        $query = $filter->apply($query);

        $query->select([
            'users.id',
            'users.full_name',
            'users.email',
            'users.phone',
            'users.created_at'
        ]);

        $query->withCount([
            'subscriptions as active_subscriptions_count' => function ($q) {
                $q->where('status', SubscriptionStatus::PAID->value);
            }
        ]);

        $query->latest();

        return $query->limit($limit)->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'active_subscriptions_count' => $user->active_subscriptions_count ?? 0,
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d') : '-',
            ];
        })->toArray();
    }

    public function getUserById(int $id): User
    {
        /** @var User $user */
        $user = User::with([
            'subscriptions' => function($q) {
                $q->where('status', SubscriptionStatus::PAID->value)->with('workshop');
            },
            'country'
        ])
            ->withCount([
                'subscriptions as active_subscriptions_count' => function ($q) {
                    $q->where('status', SubscriptionStatus::PAID->value);
                }
            ])
            ->findOrFail($id);
        
        return $user;
    }

    public function getUserStats(): array
    {
        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'deleted' => User::onlyTrashed()->count(),
        ];
    }

    public function getFilterOptions(): array
    {
        return [];
    }

    public function createUser(array $data): User
    {
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        } elseif (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return User::create($data);
    }

    public function updateUser(User $user, array $data): bool
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        return $user->update($data);
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    public function restoreUser(int $id): bool
    {
        $user = User::onlyTrashed()->findOrFail($id);
        return $user->restore();
    }

    public function permanentlyDeleteUser(int $id): bool
    {
        $user = User::onlyTrashed()->findOrFail($id);
        return $user->forceDelete();
    }

    public function toggleUserStatus(User $user): bool
    {
        return $user->update(['is_active' => !$user->is_active]);
    }

    public function bulkDeleteUsers(array $userIds): int
    {
        return User::whereIn('id', $userIds)->delete();
    }

    public function bulkToggleUserStatus(array $userIds, bool $status): int
    {
        return User::whereIn('id', $userIds)->update(['is_active' => $status]);
    }

    public function getUserActivity(User $user): array
    {
        return [];
    }
}

