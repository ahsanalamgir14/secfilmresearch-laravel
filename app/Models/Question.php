<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = ['id' ,'right_answer_id' ];

    public function answers()
    {
        return $this->hasMany(Answar::class , 'question_id');
    }
    public function rightAnswer()
    {
        return $this->belongsTo(Answar::class , 'right_answer_id');
    }

}
