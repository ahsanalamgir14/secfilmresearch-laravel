<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;
    protected $guarded = ['id'];   
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function participant_answers()
    {
        return $this->hasMany(UserAnswers::class , 'participant_id');
    }
    public function Watch_logs()
    {
        return $this->hasMany(WatchLog::class , 'participant_id');
    }

}
