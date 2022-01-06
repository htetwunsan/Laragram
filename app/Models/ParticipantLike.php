<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ParticipantLike
 *
 * @property int $id
 * @property int $participant_id
 * @property int $likeable_id
 * @property string $likeable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $likeable
 * @property-read \App\Models\Participant $participant
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike whereParticipantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParticipantLike whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
