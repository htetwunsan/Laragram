<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Log;

/**
 * App\Models\SeenMessage
 *
 * @property-read \App\Models\Message $message
 * @property-read \App\Models\Participant $participant
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $message_id
 * @property int $participant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage whereParticipantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage whereUpdatedAt($value)
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|SeenMessage whereUserId($value)
 */
class SeenMessage extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'message_id'];

    protected static function booted()
    {
        static::creating(function (SeenMessage $seenMessage) {

            if ($seenMessage->participant_id === $seenMessage->message->participant_id) {
                throw ValidationException::withMessages(['Already seen']);
            }
            $seenMessage->user_id = $seenMessage->participant->user_id;
        });
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
