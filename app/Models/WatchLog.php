<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function video()
    {
        return $this->belongsTo(Vidoe::class , 'video_id');
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class , 'participant_id');
    }


}
