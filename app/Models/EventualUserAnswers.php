<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventualUserAnswers extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}

