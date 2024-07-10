<?php

namespace A17\TwillFeatureFlags\Services;

class Helpers
{
    public static function load(): void
    {
        require_once __DIR__ . '/../Support/helpers.php';
    }
}
