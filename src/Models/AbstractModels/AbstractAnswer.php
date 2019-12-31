<?php
/**
 * Model object generated by: Skipper (http://www.skipper18.com)
 * Do not modify this file manually.
 */

namespace Sniper7Kills\Survey\Models\AbstractModels;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractAnswer extends Model
{
    /**  
     * Primary key type.
     * 
     * @var string
     */
    protected $keyType = 'bigInteger';
    
    /**  
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'response_id' => 'integer',
        'question_id' => 'string',
        'answer' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    public function response()
    {
        return $this->belongsTo('\Sniper7Kills\Survey\Models\Response', 'response_id', 'id');
    }
    
    public function question()
    {
        return $this->belongsTo('\Sniper7Kills\Survey\Models\Question', 'question_id', 'id');
    }
    
    public function options()
    {
        return $this->belongsToMany('\Sniper7Kills\Survey\Models\Option', 'survey_option_answers', 'answer_id', 'option_id');
    }
}