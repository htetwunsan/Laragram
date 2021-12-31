<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeletedMessage
 *
 * @property int $id
 * @property int $room_id
 * @property int $message_id
 * @property int $participant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Message $message
 * @property-read \App\Models\Participant $participant
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage whereParticipantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeletedMessage extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'message_id'];

    protected static function booted()
    {
        static::creating(function (DeletedMessage $deletedMessage) {
            $deletedMessage->room_id = $deletedMessage->message->room_id;
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
