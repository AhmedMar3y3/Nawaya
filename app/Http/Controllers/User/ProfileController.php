<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Models\SupprotMessage;
use App\Http\Resources\Profile\ProfileResource;
use App\Http\Requests\API\Profile\UpdateProfileRequest;
use App\Http\Requests\API\Profile\StoreSupportMessageRequest;

class ProfileController extends Controller
{

    public function getProfileDetails(Request $request)
    {
        return $this->successWithDataResponse(new ProfileResource($request->user()), null, null, 'profile');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());
        return $this->successResponse(__('messages.profile_updated_successfully'));
    }

    public function toggleNotifications(Request $request)
    {
        $user              = $request->user();
        $user->is_notified = ! $user->is_notified;
        $user->save();
        return $this->successResponse(__('messages.updated_successfully'));
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();
        return $this->successResponse(__('messages.account_deleted_successfully'));
    }

    // public function supportMessage(StoreSupportMessageRequest $request)
    // {
    //     SupprotMessage::create($request->validated() + ['user_id' => $request->user()->id]);
    //     return $this->successResponse(__('messages.sent_successfully'));
    // }

    // public function aboutApp()
    // {
    //     return $this->successWithDataResponse(Settings::where('key', 'about_app')->get('value'), null, null, 'content');
    // }

    // public function terms()
    // {
    //     return $this->successWithDataResponse(Settings::where('key', 'terms_of_use')->get('value'), null, null, 'content');
    // }

    // public function privacy()
    // {
    //     return $this->successWithDataResponse(Settings::where('key', 'privacy_policy')->get('value'), null, null, 'content');
    // }

    // public function telegram()
    // {
    //     return $this->successWithDataResponse(Settings::where('key', 'telegram_channel')->get('value'), null, null, 'content');
    // }


}
