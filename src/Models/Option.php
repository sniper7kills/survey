<?php
namespace Sniper7Kills\Survey\Models;

use Ramsey\Uuid\Uuid;

class Option extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractOption
{
    /**
     * Method to ensure new Survey's get a unique UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Option $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
}
