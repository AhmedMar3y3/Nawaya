<?php

namespace App\Http\Requests\Admin\Setting;

use App\Http\Requests\BaseRequest;

class UpdateSettingsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [

            // Main settings
            'welcome'                   => 'nullable|string',
            'logo'                      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'logo_url'                  => 'nullable|string',
            'whatsapp'                  => 'nullable|string',

            // Invoice settings
            'address'                   => 'nullable|string',
            'phone_number'              => 'nullable|string',
            'tax_number'                => 'nullable|string',

            // Workshop settings
            'workshop_policy'           => 'nullable|string',
            'workshop_returning_policy' => 'nullable|string',

            // Socialmedia settings
            'facebook'                  => 'nullable|string',
            'instgram'                  => 'nullable|string',
            'tiktok'                    => 'nullable|string',
            'twitter'                   => 'nullable|string',
            'snapchat'                  => 'nullable|string',

            // Bank account settings
            'account_name'              => 'nullable|string',
            'bank_name'                 => 'nullable|string',
            'IBAN_number'               => 'nullable|string',
            'account_number'            => 'nullable|string',
            'swift'                     => 'nullable|string',

            // Payment settings
            'online_payment'            => 'nullable|boolean',
            'bank_transfer'             => 'nullable|boolean',
        ];
    }
}
