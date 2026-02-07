<?php

namespace App\Helpers;

class DateHelper
{
    /**
     * @throws \DateMalformedStringException
     */
    public static function addMinutes(string $datetime, int $minutes): string
    {
        $date = new \DateTimeImmutable($datetime);
        $newDate = $date->modify("+$minutes minutes");
        return $newDate->format('Y-m-d H:i:s');
    }
}