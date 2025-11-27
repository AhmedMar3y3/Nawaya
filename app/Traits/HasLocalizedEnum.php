<?php
namespace App\Traits;

trait HasLocalizedEnum
{
    public function getLocalizedName(): string
    {
        $enumName = strtolower(class_basename($this));
        $key      = $this->value;

        // Try with underscore format first (workshop_type -> workshop_types)
        $enumNameWithUnderscore = str_replace('type', '_types', $enumName);
        $translationKey = "enums.{$enumNameWithUnderscore}.{$key}";
        $translation    = __($translationKey, [], 'ar');

        // If not found, try without underscore format
        if ($translation === $translationKey) {
            $translationKey = "enums.{$enumName}s.{$key}";
            $translation    = __($translationKey, [], 'ar');
        }

        if ($translation === $translationKey) {
            return $this->getFallbackName();
        }

        return $translation;
    }

    public function localized(): string
    {
        return $this->getLocalizedName();
    }

    private function getFallbackName(): string
    {
        $enumName = strtolower(class_basename($this));
        $key      = $this->value;

        $fallbacks = [
            'secondarysection' => [
                'literal'    => 'أدبي',
                'scientific' => 'علمي',
            ],
            'gender'           => [
                'male'   => 'ذكر',
                'female' => 'أنثى',
            ],
            'difficulty'           => [
                'easy'   => 'سهل',
                'medium' => 'متوسط',
                'hard'   => 'صعب',
            ],
            'level'           => [
                'beginner'   => 'مبتدئ',
                'intermediate' => 'متوسط',
                'advanced'   => 'متقدم',
            ],
            'questionkind'           => [
                'verbal'   => 'لفظي',
                'quantitative' => 'كمّي',
            ],
            'workshoptype' => [
                'online'        => 'أونلاين',
                'onsite'        => 'حضوري',
                'online_onsite' => 'أونلاين و حضوري',
                'recorded'      => 'مسجلة',
            ],
            'workshopattachmenttype' => [
                'image' => 'صورة',
                'video' => 'ملف فيديو',
            ],
        ];

        return $fallbacks[$enumName][$key] ?? $key;
    }

    public static function getLocalizedOptions(): array
    {
        $options  = [];
        $enumName = strtolower(class_basename(new \ReflectionClass(static::class)));

        foreach (static::cases() as $case) {
            $options[$case->value] = $case->getLocalizedName();
        }

        return $options;
    }
}
