<?php
/**
 * Model object generated by: Skipper (http://www.skipper18.com)
 * Do not modify this file manually.
 */

namespace Sniper7Kills\Survey\Models\AbstractModels;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractQuestion extends Model
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
    protected $attributes = [
        'type' => "text",
        'required' => True
    ];
    
    /**  
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'survey_id' => 'string',
        'question' => 'string',
        'type' => 'string',
        'required' => 'boolean',
        'order' => 'integer',
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
        'survey_id',
        'question',
        'type',
        'required',
        'order'
    ];
    
    public function survey()
    {
        return $this->belongsTo('\Sniper7Kills\Survey\Models\Survey', 'survey_id', 'id');
    }
    
    public function options()
    {
        return $this->hasMany('\Sniper7Kills\Survey\Models\Option', 'question_id', 'id');
    }
    
    public function answers()
    {
        return $this->hasMany('\Sniper7Kills\Survey\Models\Answer', 'question_id', 'id');
    }
}
