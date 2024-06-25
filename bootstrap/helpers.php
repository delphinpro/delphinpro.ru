<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

use Illuminate\Support\Collection;

function getTimeZoneList(): Collection
{
    return Cache::rememberForever('timezones_list_collection', static function () {
        $timestamp = time();
        $timezones = [];

        foreach (timezone_identifiers_list(DateTimeZone::ALL) as $value) {
            date_default_timezone_set($value);
            $timezones[$value] = $value.' (UTC '.date('P', $timestamp).')';
        }

        return collect($timezones)->sortKeys();
    });
}

function getUserTimeZone(): ?string
{
    return auth()?->user()->settings['timezone'] ?? config('app.timezone');
}

/**
 * Плюрализация для русского языка
 *
 * @param  array  $forms
 * @param  int    $number
 *
 * @return string
 */
function pluralize(array $forms, int $number): string
{
    [$many, $one, $two] = $forms;

    if ($number >= 5 && $number <= 20) {
        return $many;
    }

    $n = $number % 10;

    if ($n === 1) {
        return $one;
    }

    if (in_array($n, [2, 3, 4], true)) {
        return $two;
    }

    return $many;
}

function fmtSql(string $sql): string
{
    $repl = [
        'select'   => 'SELECT',
        'from'     => 'FROM',
        'where'    => 'WHERE',
        'and'      => 'AND',
        'order by' => 'ORDER BY',
        'group by' => 'GROUP BY',
        'or '      => 'OR ',
        'SELECT'   => "\nSELECT",
        'FROM'     => "\nFROM",
        'WHERE'    => "\nWHERE",
        'AND'      => "\nAND",
        'OR '      => "\nOR ",
        'ORDER BY' => "\nORDER BY",
        'GROUP BY' => "\nGROUP BY",
    ];

    return str_replace(array_keys($repl), array_values($repl), $sql);
}
