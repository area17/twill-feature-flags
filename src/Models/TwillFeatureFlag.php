<?php

namespace A17\TwillFeatureFlags\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Str;
use A17\Twill\Models\Behaviors\HasRevisions;

/**
 * @property string $code
 * @property string $title
 * @property string $ip_addresses
 * @property bool $publicly_available
 * @property string $publicly_available_yes_no
 * @property string|null $publicly_available_ips
 * @property bool $published
 */
class TwillFeatureFlag extends Model
{
    use HasRevisions;

    protected $fillable = ['published', 'title', 'description', 'code', 'publicly_available', 'ip_addresses'];

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []): bool
    {
        $this->code ??= Str::slug($this->title);

        return parent::save($options);
    }

    public function getPubliclyAvailableYesNoAttribute(): string
    {
        return $this->publicly_available ? 'Yes' : '';
    }

    public function getPubliclyAvailableIpsAttribute(): string|null
    {
        return $this->ip_addresses ?? null;
    }
}
