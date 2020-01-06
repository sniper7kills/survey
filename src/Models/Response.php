<?php
namespace Sniper7Kills\Survey\Models;

use Ramsey\Uuid\Uuid;

class Response extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractResponse
{
    /**
     * Method to ensure new Response's get a unique UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Response $model) {
            // Create UUID
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
}
