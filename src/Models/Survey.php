<?php
namespace Sniper7Kills\Survey\Models;

use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Survey extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractSurvey
{
    use HasSlug;

    /**
     * Eager Load these models
     */
    protected $with = ['questions'];

    /**
     * Method to ensure new Survey's get a unique UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Survey $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return Config::get('survey.identifier','slug');
    }

    public function getSurveyIdentifier()
    {
        return $this->getRouteKey();
    }


}
