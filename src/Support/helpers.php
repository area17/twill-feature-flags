<?php

use A17\TwillFeatureFlags\Repositories\TwillFeatureFlagRepository;

if (!function_exists('feature')) {
    function feature(string $code): bool
    {
        return app(TwillFeatureFlagRepository::class)->feature($code);
    }
}

if (!function_exists('feature_list')) {
    function feature_list(bool $all = false): array
    {
        return (new TwillFeatureFlagRepository())->featureList($all);
    }
}

if (!function_exists('features_can_be_public_on_twill')) {
    function features_can_be_public_on_twill(): bool
    {
        $sessionDomain = config('session.domain');

        $twillAdminAppHost = parse_url(config('twill.admin_app_url') ?? '')['host'] ?? null;

        $appDomainHost = parse_url(config('app.url') ?? '')['host'] ?? null;

        // If the admin app is not set it probably means Twill is in the same domain of the frontend
        if (blank($twillAdminAppHost)) {
            return true;
        }

        // Otherwise the domain must be the same
        if ($twillAdminAppHost === $appDomainHost) {
            return true;
        }

        // Otherwise the session domain must be set
        return filled($sessionDomain);
    }
}
