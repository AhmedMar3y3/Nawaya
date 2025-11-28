<?php
namespace App\Http\Requests\Admin\Workshop;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkshopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('type') ?? $this->workshop?->type;

        return [
            // Common fields
            'title'                        => ['required', 'string', 'max:255'],
            'teacher'                      => ['required', 'string', 'max:255'],
            'teacher_percentage'           => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description'                  => ['nullable', 'string'],
            'subject_of_discussion'        => ['required', 'string'],
            'type'                         => ['required', 'string', 'in:online,onsite,online_onsite,recorded'],

            // Packages
            'packages'                     => ['nullable', 'array'],
            'packages.*.id'                => ['nullable', 'integer', 'exists:workshop_packages,id'],
            'packages.*.title'             => ['required_with:packages.*', 'string', 'max:255'],
            'packages.*.price'             => ['required_with:packages.*', 'numeric', 'min:0'],
            'packages.*.is_offer'          => ['sometimes', 'nullable', 'in:1'],
            'packages.*.offer_price'       => ['nullable', 'required_if:packages.*.is_offer,1', 'numeric', 'min:0'],
            'packages.*.offer_expiry_date' => ['nullable', 'date'],
            'packages.*.features'          => ['nullable', 'string'],

            // Attachments
            'attachments'                  => ['nullable', 'array'],
            'attachments.*.id'             => ['nullable', 'integer', 'exists:workshop_attachments,id'],
            'attachments.*.type'           => ['required_with:attachments.*', 'in:audio,video'],
            'attachments.*.title'          => ['required_with:attachments.*', 'string', 'max:255'],
            'attachments.*.notes'          => ['nullable', 'string'],
          'attachments.*.file' => [
    'nullable',
    'file',
    function ($attribute, $value, $fail) {
        if ($value && $value->isValid()) {
            $mimeType = $value->getMimeType();
            $extension = strtolower($value->getClientOriginalExtension());

            $allowedMimes = [
                'audio/mpeg',
                'audio/mp3',
                'audio/x-mpeg-3',
                'video/mp4',
                'video/mpeg',
                'video/x-m4v',
                'application/octet-stream',
            ];

            $allowedExtensions = ['mp3', 'mp4'];

            if (!in_array($extension, $allowedExtensions) && !in_array($mimeType, $allowedMimes)) {
                $fail('الملف الصوتي أو المرئي يجب أن يكون mp3 أو mp4 فقط');
            }
        }
    },
],

            'files'                        => ['nullable', 'array'],
            'files.*.id'                   => ['nullable', 'integer', 'exists:workshop_files,id'],
            'files.*.title'                => ['required_with:files.*', 'string', 'max:255'],
            'files.*.file'                 => ['nullable', 'file'], // أو أضف mimes لو عايز

            // Recordings
            'recordings'                   => ['nullable', 'array'],
            'recordings.*.id'              => ['nullable', 'integer', 'exists:workshop_recordings,id'],
            'recordings.*.title'           => ['required_with:recordings.*', 'string', 'max:255'],
            'recordings.*.link'            => ['required_with:recordings.*', 'url', 'max:1000'],

            // Workshop type specific fields
            'start_date'                   => [in_array($type, ['online', 'onsite', 'online_onsite']) ? 'required' : 'nullable', 'date'],
            'start_time'                   => [in_array($type, ['online', 'onsite', 'online_onsite']) ? 'required' : 'nullable', 'date_format:H:i'],
            'end_date'                     => [$type === 'online_onsite' ? 'required' : 'nullable', 'date', 'after_or_equal:start_date'],
            'end_time'                     => [$type === 'online_onsite' ? 'required' : 'nullable', 'date_format:H:i'],
            'online_link'                  => [in_array($type, ['online', 'online_onsite']) ? 'required' : 'nullable', 'url'],
            'city'                         => [in_array($type, ['onsite', 'online_onsite']) ? 'required' : 'nullable', 'string'],
            'country_id'                   => [in_array($type, ['onsite', 'online_onsite']) ? 'required' : 'nullable', 'exists:countries,id'],
            'hotel'                        => ['nullable', 'string'],
            'hall'                         => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type     = $this->input('type');
            $packages = $this->input('packages', []);

            if ($type === 'recorded' && (! is_array($packages) || count($packages) !== 1)) {
                $validator->errors()->add('packages', 'الورشة المسجلة يجب أن تحتوي على حزمة واحدة فقط');
            } elseif ($type !== 'recorded' && empty($packages)) {
                $validator->errors()->add('packages', 'يجب إضافة حزمة واحدة على الأقل');
            }

            if ($type === 'recorded' && (! $this->has('recordings') || count($this->input('recordings')) < 1)) {
                $validator->errors()->add('recordings', 'يجب إضافة تسجيل واحد على الأقل للورشة المسجلة');
            }
        });
    }

    protected function prepareForValidation()
    {
        if ($this->has('teacher_percentage')) {
            $this->merge([
                'teacher_per' => $this->input('teacher_percentage'),
            ]);
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson() || $this->ajax()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
        parent::failedValidation($validator);
    }

    public function messages(): array
    {
        return [
            'attachments.*.file.mimes'     => 'الملف الصوتي أو المرئي يجب أن يكون mp3 أو mp4 فقط',
            'attachments.*.file.mimetypes' => 'صيغة الملف غير مدعومة، يجب أن يكون mp3 أو mp4',
            'files.*.file.mimes'           => 'نوع الملف غير مسموح',
        ];
    }
}
