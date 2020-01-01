<?php
namespace Sniper7Kills\Survey\Models;

use Ramsey\Uuid\Uuid;

class Question extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractQuestion
{
    protected $with = ['options'];

    /**
     * Method to ensure new Survey's get a unique UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Question $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    public function options()
    {
        return parent::options()
            ->orderBy('order')
            ->orderBy('value');
    }
}
