<?php

namespace App\Twill\Capsules\FeatureFlags\Services;

class Helpers
{
    public static function load()
    {
        require app_path('Twill/Capsules/FeatureFlags/Support/helpers.php');
    }
}
