<?php

use App\Twill\Capsules\FeatureFlags\Repositories\FeatureFlagRepository;

if (!function_exists('feature')) {
    function feature($code): bool
    {
        return app(FeatureFlagRepository::class)->feature($code);
    }
}

if (!function_exists('feature_list')) {
    function feature_list(): array
    {
        return app(FeatureFlagRepository::class)->featureList();
    }
}
