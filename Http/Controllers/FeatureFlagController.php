<?php

namespace App\Twill\Capsules\FeatureFlags\Http\Controllers;

use A17\Twill\Http\Controllers\Admin\ModuleController;

class FeatureFlagController extends ModuleController
{
    protected $moduleName = 'featureFlags';

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

        'publicly_available_ips' => [
            'title' => 'Publicly available to (IPs)',
            'field' => 'publicly_available_ips',
            'sort' => true,
        ],
    ];

    public $previewView = Templates::NO_PREVIEW;
}
