<?php

namespace App\Twill\Capsules\FeatureFlags\Models;

use A17\Twill\Models\Revision;

class FeatureFlagRevision extends Revision
{
    protected $table = 'feature_flag_revisions';
}
