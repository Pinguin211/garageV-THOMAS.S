<?php

namespace App\Service;


class NormalizeInterface
{
    public static function normalizeText(string $text): string
    {
        return trim($text);
    }

    public static function normalizePhone(string $phone): string
    {
        $phone = self::normalizeText($phone);
        return str_replace(' ', '', $phone);
    }
}