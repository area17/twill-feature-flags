<?php

namespace A17\TwillFeatureFlags\Services;

class Helpers
{
    public static function load(): void
    {
        require app_path('Twill/Capsules/FeatureFlags/Support/helpers.php');
    }
}