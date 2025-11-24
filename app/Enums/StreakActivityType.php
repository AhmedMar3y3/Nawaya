<?php

namespace App\Enums;

enum StreakActivityType: string
{
    case DAILY_CHALLENGE = 'daily_challenge';
    case FULL_TEST = 'full_test';
    case PRACTICE = 'practice';
    case QUIZ = 'quiz';
    case STREAK_RESTORE = 'streak_restore';
    
    public function getDisplayName(): string
    {
        return match($this) {
            self::DAILY_CHALLENGE => 'التحدي اليومي',
            self::FULL_TEST => 'اختبار كامل',
            self::PRACTICE => 'التدريب',
            self::QUIZ => 'اختبار سريع',
            self::STREAK_RESTORE => 'استعادة السلسلة',
        };
    }
    
    public function getXpReward(): int
    {
        return match($this) {
            self::DAILY_CHALLENGE => 50,
            self::FULL_TEST => 100,
            self::PRACTICE => 25,
            self::QUIZ => 15,
            self::STREAK_RESTORE => 0,
        };
    }
}
