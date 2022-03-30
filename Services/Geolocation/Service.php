<?php

namespace App\Twill\Capsules\FeatureFlags\Services\Geolocation;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\IpUtils;

class Service
{
    public function ipAddress(): string
    {
        $ipAddress =
            request()->header('True-Client-IP') ?? (explode(',', request()->header('X-Forwarded-For'))[0] ?? null);

        if (!$this->isIPv6($ipAddress)) {
            $ipAddress = Str::before($ipAddress, ':');
        }

        return (filled($ipAddress)
                ? $ipAddress
                : filled($ipAddress = request()->ip()))
            ? $ipAddress
            : null ?? request()->getClientIp();
    }

    public function isIPv6($ipAddress)
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    public function currentIpAddressIsOnList($ipAddresses): bool
    {
        return IpUtils::checkIp($this->ipAddress(), $ipAddresses);
    }
}
