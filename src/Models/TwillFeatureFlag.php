<?php

namespace A17\TwillFeatureFlags\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Str;
use A17\Twill\Models\Behaviors\HasRelated;
use A17\Twill\Models\Behaviors\HasRevisions;
use Illuminate\Foundation\Auth\User as AuthenticatableContract;

/**
 * @property string $code
 * @property string $title
 * @property string $ip_addresses
 * @property bool $publicly_available
 * @property string $publicly_available_yes_no
 * @property string|null $publicly_available_ips
 * @property bool $published
 * @property bool $publicly_available_twill_users
 */
class TwillFeatureFlag extends Model
{
    use HasRelated;
    use HasRevisions;

    protected $fillable = [
        'published',
        'title',
        'description',
        'code',
        'publicly_available',
        'ip_addresses',
        'publicly_available_twill_users',
    ];

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

    public function getPubliclyAvailableTwillUsersYesNoAttribute(): string
    {
        return $this->publicly_available_twill_users ? 'Yes' : '';
    }

    public function getPubliclyAvailableIpsAttribute(): string|null
    {
        return $this->ip_addresses ?? null;
    }

    public function userIsPubliclyAllowed(AuthenticatableContract|null $user): bool
    {
        if ($user === null) {
            return false;
        }

        /** @phpstan-ignore-next-line */
        if ($user->published === false) {
            return false;
        }

        /** @phpstan-ignore-next-line */
        if ($user->isSuperAdmin()) {
            return true;
        }

        $allowedUsers = $this->getRelated('allowed_twill_users');

        if ($allowedUsers->isEmpty()) {
            return true;
        }

        /** @phpstan-ignore-next-line */
        return $allowedUsers->pluck('email')->contains($user->email);
    }
}
