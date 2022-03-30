<?php

namespace App\Twill\Capsules\FeatureFlags\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use App\Twill\Capsules\FeatureFlags\Models\FeatureFlag;
use App\Twill\Capsules\FeatureFlags\Services\Geolocation\Service;

class FeatureFlagRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(FeatureFlag $model)
    {
        $this->model = $model;
    }

    public function feature($code): bool
    {
        try {
            $featureFlag = $this->model->where('code', $code)->first();
        } catch (\Throwable $_) {
            return false;
        }

        if (blank($featureFlag) || !$featureFlag->published) {
            return false;
        }

        if (!$this->isRealProduction() || $this->isPubliclyAvailableToCurrentUser($featureFlag)) {
            return true;
        }

        return $featureFlag->publicly_available;
    }

    private function isRealProduction(): bool
    {
        return collect(config('app.domains.publicly_available'))->contains(request()->getHost());
    }

    public function featureList(): array
    {
        return $this->model
            ->all()
            ->filter(fn($feature) => $this->feature($feature->code))
            ->pluck('code')
            ->toArray();
    }

    private function isPubliclyAvailableToCurrentUser($featureFlag): bool
    {
        return app(Service::class)->currentIpAddressIsOnList(
            collect(explode(',', $featureFlag->ip_addresses))
                ->map(fn($ip) => trim($ip))
                ->toArray(),
        );
    }
}
