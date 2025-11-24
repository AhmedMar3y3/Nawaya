<?php

namespace App\Enums;

enum SubscriptionType: string
{
    case TRIAL = 'trial';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
}

