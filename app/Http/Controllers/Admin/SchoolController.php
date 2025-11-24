<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\School;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Filters\SchoolFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\School\StoreSchoolRequest;
use App\Http\Requests\Admin\School\UpdateSchoolRequest;

class SchoolController extends Controller
{
    public function index(Request $request): View
    {
        $query         = School::query()->with('city')->withCount('users');
        $filter        = new SchoolFilter($query, $request);
        $filteredQuery = $filter->apply();
        $schools       = $filteredQuery->paginate(15);
        $statistics    = $this->getStatistics();
        $cities        = City::orderBy('name')->get();

        return view('Admin.schools.index', compact('schools', 'statistics', 'filter', 'cities'));
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        try {
            School::create($request->validated());
            return redirect()->route('admin.schools.index')->with('success', 'تم إنشاء المدرسة بنجاح');
        } catch (\Exception) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المدرسة');
        }
    }

    public function update(UpdateSchoolRequest $request, $school): RedirectResponse
    {
        try {
            $schoolModel = School::findOrFail($school);
            $schoolModel->update($request->validated());
            return redirect()->route('admin.schools.index')->with('success', 'تم تحديث المدرسة بنجاح');
        } catch (\Exception) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المدرسة');
        }
    }

    public function destroy($school): RedirectResponse
    {
        try {
            $schoolModel = School::findOrFail($school);

            if ($schoolModel->users()->count() > 0) {
                return redirect()->back()->with('error', 'لا يمكن حذف المدرسة لوجود مستخدمين مرتبطين بها');
            }

            $schoolModel->delete();
            return redirect()->route('admin.schools.index')->with('success', 'تم حذف المدرسة بنجاح');
        } catch (\Exception) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المدرسة');
        }
    }

    private function getStatistics(): array
    {
        return [
            'total_schools'         => School::count(),
            'schools_with_users'    => School::has('users')->count(),
            'schools_without_users' => School::doesntHave('users')->count(),
            'most_popular_school'   => School::withCount('users')
                ->orderBy('users_count', 'desc')
                ->first(),
        ];
    }
}
