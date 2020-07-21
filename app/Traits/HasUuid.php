<?php

namespace App\Traits;

use Closure;
use Illuminate\Support\Str;

/**
 * @property-read string $uuid
 */
trait HasUuid
{
    /**
     * Boot trait
     */
    protected static function bootHasUuid()
    {
        static::creating(static::getCreatingUuidHandler());
    }

    /**
     * Create a unique reference code when a Model is in the creating boot event
     *
     * @return Closure
     */
    protected static function getCreatingUuidHandler(): Closure
    {
        return function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::orderedUuid();
            }
        };
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
