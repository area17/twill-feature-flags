<?php

namespace App\Twill\Capsules\FeatureFlags\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Str;
use A17\Twill\Models\Behaviors\HasRevisions;

/**
 * @property string $code
 * @property string $title
 * @property bool $publicly_available
 */
class FeatureFlag extends Model
{
    use HasRevisions;

    protected $fillable = ['published', 'title', 'description', 'code', 'publicly_available', 'ip_addresses'];

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->code ??= Str::slug($this->title);

        return parent::save($options);
    }

    public function getPubliclyAvailableYesNoAttribute()
    {
        return $this->publicly_available ? 'Yes' : '';
    }

    public function getPubliclyAvailableIpsAttribute()
    {
        return $this->ip_addresses;
    }
}
