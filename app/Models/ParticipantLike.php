<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantLike extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'likeable_id', 'likeable_type'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /*
     * Get (message) model
    */
    public function likeable()
    {
        return $this->morphTo();
    }
}
