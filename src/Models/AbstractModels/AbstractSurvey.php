<?php
/**
 * Model object generated by: Skipper (http://www.skipper18.com)
 * Do not modify this file manually.
 */

namespace Sniper7Kills\Survey\Models\AbstractModels;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractSurvey extends Model
{
    /**  
     * Primary key type.
     * 
     * @var string
     */
    protected $keyType = 'uuid';
    
    /**  
     * Primary key is non-autoincrementing.
     * 
     * @var bool
     */
    public $incrementing = false;
    
    /**  
     * The model's default values for attributes.
     * 
     * @var array
     */
    protected $attributes = ['guests' => True];
    
    /**  
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'slug' => 'string',
        'url' => 'string',
        'description' => 'string',
        'guests' => 'boolean',
        'identifier' => 'string',
        'available_at' => 'datetime',
        'available_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    /**  
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'url',
        'description',
        'guests',
        'identifier',
        'available_at',
        'available_until'
    ];
    
    public function questions()
    {
        return $this->hasMany('\Sniper7Kills\Survey\Models\Question', 'survey_id', 'id');
    }
    
    public function responses()
    {
        return $this->hasMany('\Sniper7Kills\Survey\Models\Response', 'survey_id', 'id');
    }
}
