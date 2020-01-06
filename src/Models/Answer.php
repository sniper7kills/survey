<?php
namespace Sniper7Kills\Survey\Models;

use Ramsey\Uuid\Uuid;

class Answer extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractAnswer
{
    /**
     * Method to ensure new Answer's get a unique UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Answer $model) {
            // Create UUID
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
}
