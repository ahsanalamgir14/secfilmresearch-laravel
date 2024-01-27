<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answar extends Model
{
    use HasFactory;

    public function questions()
    {
        return $this->belongsTo(Question::class , 'question_id');
    }
}
