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
     * And assign the initial URL attribute to what the config specifies
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Survey $model) {
            // Create UUID
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
            // Set URL Attribute
            $model->setAttribute('url',$model->getAttribute(Config::get('survey.identifier','slug')));
        });
    }

    public function questions()
    {
        return parent::questions()->orderBy('order')->orderBy('question');
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
        return 'url';
    }

    /**
     * Publish the survey so its available
     */
    public function publishSurvey()
    {
        $this->update(['available_at'=>$this->freshTimestamp()]);
    }

    /**
     * Unpublish the survey so it is no longer available
     */
    public function unpublishSurvey()
    {
        $this->update(['available_at'=>null]);
    }

    /**
     * Set the URL for the survey as the ID
     */
    public function setUrlAsId()
    {
        $this->update(['url'=> $this->id]);
    }

    /**
     * Set the URL for the survey as the Slug
     */
    public function setUrlAsSlug()
    {
        $this->update(['url'=> $this->slug]);
    }

}
