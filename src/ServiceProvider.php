<?php

namespace A17\TwillFeatureFlags;

use Illuminate\Support\Str;
use A17\Twill\Facades\TwillCapsules;
use A17\Twill\TwillPackageServiceProvider;

class ServiceProvider extends TwillPackageServiceProvider
{
    protected $autoRegisterCapsules = false;

    public function boot(): void
    {
        $this->registerThisCapsule();

        parent::boot();
    }

    protected function registerThisCapsule(): void
    {
        $namespace = $this->getCapsuleNamespace();

        TwillCapsules::registerPackageCapsule(
            Str::afterLast($namespace, '\\'),
            $namespace,
            $this->getPackageDirectory().'/src'
        );
    }
}
