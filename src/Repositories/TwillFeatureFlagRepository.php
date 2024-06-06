<?php

namespace A17\TwillFeatureFlags\Repositories;

use Throwable;
use Illuminate\Support\Str;
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
            $featureFlag = TwillFeatureFlag::where('code', $code)->first();
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

    public function featureList(bool $all = false): array
    {
        $features = TwillFeatureFlag::all();

        if ($features->count() === 0) {
            return [];
        }

        if (!$all) {
            $features = $features->filter(fn(TwillFeatureFlag $feature) => $this->feature($feature->code));
        }

        return $features->pluck('code')->toArray();
    }

    private function isPubliclyAvailableToCurrentUser(TwillFeatureFlag $featureFlag): bool
    {
        return $this->isPubliclyAvailableToIpAddresses($featureFlag) ||
               $this->isPubliclyAvailableToTwillUsers($featureFlag);
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

        return $this->url($current, 'host') === $this->url($twill, 'host') &&
            Str::startsWith($this->url($current, 'path') ?? '/', $this->url($twill, 'path') ?? '/');
    }

    protected function url(array|bool $parsed, string $attribute): string|null
    {
        if ($parsed === false || !isset($parsed[$attribute])) {
            return null;
        }

        if ($parsed === true) {
            return null;
        }

        return $parsed[$attribute];
    }

    public function isPubliclyAvailableToIpAddresses(TwillFeatureFlag $featureFlag): bool
    {
        if (blank($featureFlag->ip_addresses)) {
            return false;
        }

        return (new GeolocationService())->currentIpAddressIsOnList(
            collect(explode(',', $featureFlag->ip_addresses))
                ->map(fn($ip) => trim($ip))
                ->toArray(),
        );
    }

    public function isPubliclyAvailableToTwillUsers(TwillFeatureFlag $featureFlag): bool
    {
        if (!$featureFlag->publicly_available_twill_users) {
            return false;
        }

        // Is the user authenticated on Twill?
        return auth('twill_users')->check();
    }
}
