<?php

use App\Twill\Capsules\FeatureFlags\Repositories\FeatureFlagRepository;

if (!function_exists('feature')) {
    function feature(string $code): bool
    {
        return app(FeatureFlagRepository::class)->feature($code);
    }
}

if (!function_exists('feature_list')) {
    function feature_list(): array
    {
        return (new FeatureFlagRepository())->featureList();
    }
}
