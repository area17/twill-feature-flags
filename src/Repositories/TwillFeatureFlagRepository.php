<?php

namespace A17\TwillFeatureFlags\Repositories;

use Throwable;
use Illuminate\Support\Collection;
use A17\Twill\Repositories\ModuleRepository;
use A17\TwillFeatureFlags\Models\TwillFeatureFlag;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\TwillFeatureFlags\Services\Geolocation\Service as GeolocationService;

class TwillFeatureFlagRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(TwillFeatureFlag $model = null)
    {
        $this->bootCache();

        $this->model = $model ?? new TwillFeatureFlag();
    }

    public function feature(string $code): bool
    {
        if (!is_null($result = $this->getFromCache($code))) {
            return $result;
        }

        $this->putOnCache($code, $result = $this->getFeature($code));

        return $result;
    }

    public function getFeature(string $code): bool
    {
        try {
            /** @var \A17\TwillFeatureFlags\Models\TwillFeatureFlag|null $featureFlag */
            $featureFlag = FeatureFlag::where('code', $code)->first();
        } catch (Throwable) {
            return false;
        }

        if (blank($featureFlag) || blank($featureFlag?->published) || $featureFlag?->published === false) {
            return false;
        }

        if (!$this->isRealProduction() || $this->isPubliclyAvailableToCurrentUser($featureFlag)) {
            return true;
        }

        return $featureFlag->publicly_available || $this->isRunningOnTwill();
    }

    private function isRealProduction(): bool
    {
        return (new Collection(config('app.domains.publicly_available')))->contains(request()->getHost());
    }

    public function featureList(): array
    {
        return $this->model
            ->all()
            ->filter(fn($feature) => $this->feature($feature->code))
            ->pluck('code')
            ->toArray();
    }

    private function isPubliclyAvailableToCurrentUser(TwillFeatureFlag $featureFlag): bool
    {
        return (new GeolocationService())->currentIpAddressIsOnList(
            collect(explode(',', $featureFlag->ip_addresses))
                ->map(fn($ip) => trim($ip))
                ->toArray(),
        );
    }

    private function bootCache(): void
    {
        if (app()->bound('feature-flag-cache')) {
            return;
        }

        app()->singleton('feature-flag-cache', fn() => new Cache());
    }

    private function getFromCache(string $code): bool|null
    {
        return app('feature-flag-cache')->get($code);
    }

    private function putOnCache(string $code, bool $value): void
    {
        app('feature-flag-cache')->put($code, $value);
    }

    public function isRunningOnTwill(): bool
    {
        $twillUrlPrefix = config('twill.admin_app_url');

        if (filled($path = config('twill.admin_app_path'))) {
            $twillUrlPrefix .= "/$path";
        }

        $twillUrlPrefix .= '/';

        if (!Str::startsWith($twillUrlPrefix, ['http', 'https'])) {
            $twillUrlPrefix = "https://$twillUrlPrefix";
        }

        $current = parse_url(url()->full());
        $twill = parse_url($twillUrlPrefix);

        return $current['host'] === $twill['host'] && Str::startsWith($current['path'] ?? '/', $twill['path'] ?? '/');
    }
}
