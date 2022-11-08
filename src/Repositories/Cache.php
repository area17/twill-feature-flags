<?php

namespace A17\TwillFeatureFlags\Repositories;

class Cache
{
    public array $flags = [];

    public function get(string $code): bool|null
    {
        return $this->flags[$code] ?? null;
    }

    public function put(string $code, bool $value): void
    {
        $this->flags[$code] = $value;
    }
}
