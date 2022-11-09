<?php

namespace A17\TwillFeatureFlags\Services\Geolocation;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\IpUtils;

class Service
{
    public function ipAddress(): string|null
    {
        $ipAddress = $this->getTrueClientIP() ?? $this->getXForwardedFor();

        if (!empty($ipAddress) && !$this->isIPv6($ipAddress)) {
            $ipAddress = Str::before($ipAddress, ':');
        }

        if (empty($ipAddress)) {
            $ipAddress = request()->ip() ?? request()->getClientIp();
        }

        return $ipAddress;
    }

    public function isIPv6(string $ipAddress): mixed
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    public function currentIpAddressIsOnList(array $ipAddresses): bool
    {
        if (empty(($ipAddress = $this->ipAddress()))) {
            return false;
        }

        return IpUtils::checkIp($ipAddress, $ipAddresses);
    }

    public function getTrueClientIP(): string|null
    {
        return request()->header('True-Client-IP');
    }

    public function getXForwardedFor(): string|null
    {
        $xForwardedFor = request()->header('X-Forwarded-For');

        if (empty($xForwardedFor)) {
            return null;
        }

        $xForwardedFor = explode(',', $xForwardedFor);

        return $xForwardedFor[0] ?? null;
    }
}
