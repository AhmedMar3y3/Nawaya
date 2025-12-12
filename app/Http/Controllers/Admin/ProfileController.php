<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();
        return view('Admin.profile.index', compact('admin'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $data  = $request->validated();

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $admin->update($data);

        return redirect()->route('admin.profile.index')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
