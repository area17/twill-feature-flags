<?php

use A17\TwillFeatureFlags\Repositories\TwillFeatureFlagRepository;

if (!function_exists('feature')) {
    function feature(string $code): bool
    {
        return app(TwillFeatureFlagRepository::class)->feature($code);
    }
}

if (!function_exists('feature_list')) {
    function feature_list(): array
    {
        return (new TwillFeatureFlagRepository())->featureList();
    }
}
