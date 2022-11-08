<?php

namespace A17\TwillFeatureFlags\Http\Requests;

use A17\Twill\Http\Requests\Admin\Request;

class TwillFeatureFlagRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [];
    }

    public function rulesForUpdate(): array
    {
        return [];
    }
}
