<?php

namespace A17\TwillFeatureFlags\Http\Controllers;

use A17\Twill\Facades\TwillCapsules;
use A17\Twill\Exceptions\NoCapsuleFoundException;
use A17\Twill\Http\Controllers\Admin\ModuleController;

class TwillFeatureFlagController extends ModuleController
{
    protected $moduleName = 'twillFeatureFlags';

    protected $indexOptions = [
        'permalink' => false,
    ];

    protected $indexColumns = [
        'title' => [
            'title' => 'Title',
            'field' => 'title',
            'sort' => true,
        ],

        'publicly_available' => [
            'title' => 'Publicly available',
            'field' => 'publicly_available_yes_no',
            'sort' => true,
        ],

        'publicly_available_twill_users' => [
            'title' => 'Publicly available for Twill users',
            'field' => 'publicly_available_twill_users_yes_no',
            'sort' => true,
        ],

        'publicly_available_ips' => [
            'title' => 'Publicly available to (IPs)',
            'field' => 'publicly_available_ips',
            'sort' => true,
        ],
    ];

    protected function getViewPrefix(): ?string
    {
        return 'resources.views.admin';
    }
}
