<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingsRequest;

class SettingController extends Controller
{
    public function index()
    {
        $data = Setting::pluck('value', 'key');
        return view('Admin.settings.index', compact('data'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('settings', 'public');
            $logoUrl = asset('storage/' . $logoPath);
            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $logoUrl]
            );
            unset($data['logo']);
        } elseif (isset($data['logo_url']) && $data['logo_url']) {
            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $data['logo_url']]
            );
            unset($data['logo_url']);
        }
        
        foreach ($data as $key => $value) {
            if ($key !== 'logo' && $key !== 'logo_url') {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
        
        return redirect()->route('admin.settings.index')->with('success', 'تم تحديث الإعدادات بنجاح.');
    }
}
