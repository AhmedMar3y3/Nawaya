<?php

namespace App\Enums;

use App\Traits\HasLocalizedEnum;

enum ChallengeType: string {
    use HasLocalizedEnum;

    case DAILY_CHALLENGE = 'daily_challenge';
    case FINAL_TEST = 'final_test';
    case PRACTICE = 'practice';
}

