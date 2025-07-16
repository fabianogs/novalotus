<?php

namespace App\Traits;

use App\Services\ActivityLogService;

trait Loggable
{
    /**
     * Boot do trait
     */
    protected static function bootLoggable()
    {
        static::created(function ($model) {
            ActivityLogService::logCrudAction('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            ActivityLogService::logCrudAction('update', $model, $model->getOriginal(), $model->getAttributes());
        });

        static::deleted(function ($model) {
            ActivityLogService::logCrudAction('delete', $model, $model->getAttributes(), null);
        });
    }
} 