<?php

namespace A17\TwillFeatureFlags\Support\Facades;

use Illuminate\Support\Facades\Facade;
use A17\TwillFeatureFlags\Support\TwillFeatureFlags as TwillFeatureFlagsService;

class TwillFeatureFlags extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwillFeatureFlagsService::class;
    }
}
