<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
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
