<?php

namespace App\Helpers;

class FormatArabicDates
{

    public static function formatArabicDate($date): string
    {
        $arabicMonths = [
            1  => 'يناير' , 2   => 'فبراير', 3  => 'مارس',
            4  => 'أبريل' , 5   => 'مايو'  , 6    => 'يونيو',
            7  => 'يوليو' , 8   => 'أغسطس' , 9   => 'سبتمبر',
            10 => 'أكتوبر', 11 => 'نوفمبر' , 12 => 'ديسمبر',
        ];
        $monthNumber = (int) $date->format('n');
        return $date->format('d') . ' ' . $arabicMonths[$monthNumber] . ' ' . $date->format('Y');
    }
}