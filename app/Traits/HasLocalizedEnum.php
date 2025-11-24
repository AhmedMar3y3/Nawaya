<?php
namespace App\Traits;

trait HasLocalizedEnum
{
    public function getLocalizedName(): string
    {
        $enumName = strtolower(class_basename($this));
        $key      = $this->value;

        $translationKey = "enums.{$enumName}s.{$key}";
        $translation    = __($translationKey, [], 'ar');

        if ($translation === $translationKey) {
            return $this->getFallbackName();
        }

        return $translation;
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
