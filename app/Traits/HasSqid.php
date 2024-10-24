<?php

namespace App\Traits;

use App\Utility\Sqid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

trait HasSqid
{
    /**
     * Get the obfuscated version of the model Id.
     *
     * @see https://sqids.org
     */
    protected function sqid(): Attribute
    {
        return Attribute::make(
            get: fn () => Sqid::encode($this->id)
        );
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'sqid';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBindingQuery($this, Sqid::decode($value), 'id')->first();
    }
}
