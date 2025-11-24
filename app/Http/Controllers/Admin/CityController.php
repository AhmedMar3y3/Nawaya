<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\View\View;
use App\Filters\CityFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\City\StoreCityRequest;
use App\Http\Requests\Admin\City\UpdateCityRequest;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        $query         = City::query()->withCount(['users', 'schools']);
        $filter        = new CityFilter($query, $request);
        $filteredQuery = $filter->apply();
        $cities        = $filteredQuery->paginate(15);
        $statistics    = $this->getStatistics();

        return view('Admin.cities.index', compact('cities', 'statistics', 'filter'));
    }

    public function store(StoreCityRequest $request): RedirectResponse
    {
        try {
            City::create($request->validated());
            return redirect()->route('admin.cities.index')->with('success', 'تم إنشاء المدينة بنجاح');
        } catch (\Exception) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء المدينة');
        }
    }

    public function update(UpdateCityRequest $request, City $city): RedirectResponse
    {
        try {
            $city->update($request->validated());
            return redirect()->route('admin.cities.index')->with('success', 'تم تحديث المدينة بنجاح');
        } catch (\Exception) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث المدينة');
        }
    }

    public function destroy(City $city): RedirectResponse
    {
        try {
            
            if ($city->users()->count() > 0) {
                return redirect()->back()->with('error', 'لا يمكن حذف المدينة لوجود مستخدمين مرتبطين بها');
            }

            if ($city->schools()->count() > 0) {
                return redirect()->back()->with('error', 'لا يمكن حذف المدينة لوجود مدارس مرتبطة بها');
            }

            $city->delete();
            return redirect()->route('admin.cities.index')->with('success', 'تم حذف المدينة بنجاح');
        } catch (\Exception) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المدينة');
        }
    }

    private function getStatistics(): array
    {
        return [
            'total_cities'         => City::count(),
            'cities_with_users'    => City::has('users')->count(),
            'cities_with_schools'  => City::has('schools')->count(),
            'most_popular_city'    => City::withCount('users')->orderBy('users_count', 'desc')->first(),
            'cities_without_users' => City::doesntHave('users')->count(),
        ];
    }
}
