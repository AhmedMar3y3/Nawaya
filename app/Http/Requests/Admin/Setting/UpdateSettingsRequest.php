<?php
namespace App\Http\Requests\Admin\Setting;

use App\Http\Requests\BaseRequest;

class UpdateSettingsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'about_app'           => 'nullable|string',
            'privacy_policy'      => 'nullable|string',
            'terms_of_use'        => 'nullable|string',
            'telegram_channel'    => 'nullable|url|regex:/^https:\/\/t\.me\/[a-zA-Z0-9_]+$/',
            'referral_ord_users'  => 'nullable|integer|min:0',
            'referral_subs_users' => 'nullable|integer|min:0',
            'max_free_restores'   => 'nullable|integer|min:0|max:10',
            'per_of_easy'         => 'nullable|integer|min:0|max:100',
            'per_of_medium'       => 'nullable|integer|min:0|max:100',
            'per_of_hard'         => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
                function ($attribute, $value, $fail) {
                    $easy   = (int) $this->input('per_of_easy', 0);
                    $medium = (int) $this->input('per_of_medium', 0);
                    $hard   = (int) $value;
                    if ($easy + $medium + $hard !== 100) {
                        $fail('يجب أن يكون مجموع نسب الصعوبة السهلة والمتوسطة والصعبة يساوي 100.');
                    }
                },
            ],
        ];
    }
}
