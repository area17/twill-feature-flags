<?php

namespace App\Twill\Capsules\FeatureFlags\Http\Requests;

use A17\Twill\Http\Requests\Admin\Request;

class FeatureFlagRequest extends Request
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
