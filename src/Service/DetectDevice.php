<?php

namespace App\Service;

class DetectDevice
{
    // https://stackoverflow.com/a/10989524
    public function isMobile(string $userAgent): bool
    {
        return preg_match("/(Phone|Android|iPhone)/i", $userAgent);
    }
}