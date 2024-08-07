<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Helpers;

use Carbon\CarbonInterface;

class Display
{
    public static function bool(bool $value, string $true = 'Да', string $false = 'Нет'): string
    {
        return $value
            ? '<span class="bulb"><i class="bg-success"></i>'.$true.'</span>'
            : '<span class="bulb"><i class="bg-danger"></i>'.$false.'</span>';
    }

    public static function datetime(?CarbonInterface $carbon): string
    {
        return $carbon
            ? '<span class="font-monospace">'.$carbon->format('d.m.Y').'</span>'
            .' <small class="font-monospace text-muted"><small>'.$carbon->format('H:i').'</small></small>'
            : '';
    }
}
