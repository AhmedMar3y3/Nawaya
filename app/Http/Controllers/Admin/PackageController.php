<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Package\UpdatePackageRequest;

class PackageController extends Controller
{
    public function index(): View
    {
        $package = Package::getPackage();
        return view('Admin.packages.index', compact('package'));
    }

    public function update(UpdatePackageRequest $request): RedirectResponse
    {
        try {
            $package = Package::getPackage();
            $data = $request->validated();
            
            $data['has_discount'] = $request->boolean('has_discount');
            $data['discount_percentage'] = $data['has_discount'] ? ($data['discount_percentage'] ?? 0) : 0;
            
            $package->update($data);
            return redirect()->route('admin.packages.index')->with('success', 'تم تحديث باقة الاشتراك بنجاح');
        } catch (\Exception) {
            return redirect()->route('admin.packages.index')->with('error', 'فشل في تحديث باقة الاشتراك');
        }
    }
}