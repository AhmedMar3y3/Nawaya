<?php

namespace App\Http\Requests\Admin\Workshop;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkshopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson() || $this->ajax()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        parent::failedValidation($validator);
    }

    public function rules(): array
    {
        $type  = $this->input('type');
        $rules = [
            // Common fields
            'title'                        => ['required', 'string', 'max:255'],
            'teacher'                      => ['required', 'string', 'max:255'],
            'teacher_percentage'           => ['required', 'numeric', 'min:0', 'max:100'],
            'description'                  => ['nullable', 'string'],
            'subject_of_discussion'        => ['required'],
            'type'                         => ['required', 'string', 'in:online,onsite,online_onsite,recorded'],

            // Packages
            'packages'                     => ['required', 'array'],
            'packages.*.title'             => ['required', 'string', 'max:255'],
            'packages.*.price'             => ['required', 'numeric', 'min:0'],
            'packages.*.is_offer'          => ['sometimes', 'nullable'],
            'packages.*.offer_price'       => ['nullable', 'required_if:packages.*.is_offer,1', 'numeric', 'min:0'],
            'packages.*.offer_expiry_date' => ['nullable', 'date', 'after:today'],
            'packages.*.features'          => ['nullable', 'string'],

            // Attachments
            'attachments'                  => ['nullable', 'array'],
            'attachments.*.type'           => ['required', 'string', 'in:audio,video'],
            'attachments.*.title'          => ['required', 'string', 'max:255'],
            'attachments.*.file'           => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        $mimeType  = $value->getMimeType();
                        $extension = strtolower($value->getClientOriginalExtension());

                        $allowedMimes = [
                            'audio/mpeg',
                            'audio/mp3',
                            'audio/x-mpeg-3',
                            'video/mp4',
                            'video/mpeg',
                            'video/x-m4v',
                        ];

                        $allowedExtensions = ['mp3', 'mp4'];

                        $isValidExtension = in_array($extension, $allowedExtensions);
                        $isValidMime      = in_array($mimeType, $allowedMimes);

                        if (! $isValidExtension && ! $isValidMime) {
                            $fail('يجب أن يكون الملف من نوع mp3 أو mp4');
                        }
                    }
                },
            ],
            'attachments.*.notes'          => ['nullable', 'string'],

            // Files
            'files'                        => ['nullable', 'array'],
            'files.*.title'                => ['required', 'string', 'max:255'],
            'files.*.file'                 => ['required', 'file'],

            // Recordings (only for recorded type)
            'recordings'                   => ['nullable', 'array'],
            'recordings.*.title'           => ['required', 'string', 'max:255'],
            'recordings.*.link'            => ['required', 'url'],
        ];

        // Type-specific rules
        if (in_array($type, ['online', 'onsite', 'online_onsite'])) {
            $rules['start_date'] = ['required', 'date'];
            $rules['end_date']   = ['nullable', 'date', 'after_or_equal:start_date'];
            $rules['start_time'] = ['required', 'date_format:H:i'];
            $rules['end_time']   = ['nullable', 'date_format:H:i'];
        }

        if (in_array($type, ['online', 'online_onsite'])) {
            $rules['online_link'] = ['required', 'url'];
        }

        if (in_array($type, ['onsite', 'online_onsite'])) {
            $rules['city']       = ['required', 'string', 'max:255'];
            $rules['country_id'] = ['required', 'exists:countries,id'];
            $rules['hotel']      = ['nullable', 'string', 'max:255'];
            $rules['hall']       = ['nullable', 'string', 'max:255'];
        }

        if ($type === 'online_onsite') {
            $rules['end_date'] = ['required', 'date', 'after_or_equal:start_date'];
            $rules['end_time'] = ['required', 'date_format:H:i'];
        }

        if ($type === 'recorded') {
            $rules['recordings'] = ['required', 'array', 'min:1'];
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type     = $this->input('type');
            $packages = $this->input('packages', []);

            if ($type === 'recorded' && count($packages) !== 1) {
                $validator->errors()->add('packages', 'الورشة المسجلة يجب أن تحتوي على حزمة واحدة فقط');
            } elseif ($type !== 'recorded' && count($packages) < 1) {
                $validator->errors()->add('packages', 'يجب إضافة حزمة واحدة على الأقل');
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required'                 => 'عنوان الورشة مطلوب',
            'teacher.required'               => 'اسم المدرب مطلوب',
            'teacher_percentage.required'    => 'نسبة المدرب مطلوبة',
            'subject_of_discussion.required' => 'موضوع النقاش مطلوب',
            'type.required'                  => 'نوع الورشة مطلوب',
            'packages.required'              => 'يجب إضافة حزمة واحدة على الأقل',
            'packages.size'                  => 'الورشة المسجلة يجب أن تحتوي على حزمة واحدة فقط',
            'start_date.required'            => 'تاريخ البداية مطلوب',
            'online_link.required'           => 'رابط الزوم مطلوب',
            'city.required'                  => 'المدينة مطلوبة',
            'country_id.required'            => 'الدولة مطلوبة',
            'recordings.required'            => 'يجب إضافة تسجيل واحد على الأقل للورشة المسجلة',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Convert teacher_percentage to teacher_per for database
        if ($this->has('teacher_percentage')) {
            $this->merge([
                'teacher_per' => $this->input('teacher_percentage'),
            ]);
        }
    }
}
