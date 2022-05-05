<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Subject;

class Question extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function subject()
    {
        return $this->belongsToMany(Subject::class,'subjects_questions','question_id','subject_id');
    }
}
