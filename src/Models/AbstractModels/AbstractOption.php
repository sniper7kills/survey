<?php
/**
 * Model object generated by: Skipper (http://www.skipper18.com)
 * Do not modify this file manually.
 */

namespace Sniper7Kills\Survey\Models\AbstractModels;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractOption extends Model
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
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'question_id' => 'string',
        'value' => 'string',
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
        'question_id',
        'value',
        'order'
    ];
    
    public function question()
    {
        return $this->belongsTo('\Sniper7Kills\Survey\Models\Question', 'question_id', 'id');
    }
    
    public function answers()
    {
        return $this->belongsToMany('\Sniper7Kills\Survey\Models\Answer', 'survey_option_answers', 'option_id', 'answer_id');
    }
}
