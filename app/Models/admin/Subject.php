<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Question;

class Subject extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function question()
    {
        return $this->belongsToMany(Question::class,'subjects_questions','subject_id','question_id');
    }
}
