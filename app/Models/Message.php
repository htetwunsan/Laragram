<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Illuminate\Events\queueable;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property int $room_id
 * @property int $participant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Participant $participant
 * @property-read \App\Models\Room $room
 * @method static \Database\Factories\MessageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereParticipantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Participant[] $seenByParticipants
 * @property-read int|null $seen_by_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SeenMessage[] $seenMessages
 * @property-read int|null $seen_messages_count
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 * @property string $content_type
 * @property string $content
 * @method static \Illuminate\Database\Eloquent\Builder|Message text()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message image()
 * @method static \Illuminate\Database\Eloquent\Builder|Message like()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Participant[] $likedByParticipants
 * @property-read int|null $liked_by_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ParticipantLike[] $likes
 * @property-read int|null $likes_count
 */
class Message extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'content_type', 'content'];

    protected static function booted()
    {
        static::creating(function (Message $message) {
            $message->user_id = $message->participant->user_id;
            $message->room_id = $message->participant->room_id;
        });

        static::created(function (Message $message) {
            $message->room->touch();
        });

        static::deleting(queueable(function (Message $message) {
            $message->likes->each(function (ParticipantLike $like) {
                $like->delete();
            });
        }));
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function seenMessages()
    {
        return $this->hasMany(SeenMessage::class);
    }

    public function seeAs(Participant $participant)
    {
        return $this->seenMessages()->create(['participant_id' => $participant->id]);
    }

    public function seenByParticipants()
    {
        return $this->belongsToMany(Participant::class, SeenMessage::class, 'message_id', 'participant_id')->withTimestamps();
    }

    public function likes()
    {
        return $this->morphMany(ParticipantLike::class, 'likeable');
    }

    public function likeAs(Participant $participant): ParticipantLike
    {
        return $this->likes()->firstOrCreate(['participant_id' => $participant->id]);
    }

    /**
     * @param Participant $participant
     * @return ParticipantLike|null
     */
    public function unlikeAs(Participant $participant)
    {
        $like = $this->likes()->firstWhere('participant_id', $participant->id);

        if (!is_null($like)) {
            $like->delete();
        }

        return $like;
    }

    public function likedByParticipants()
    {
        return $this->belongsToMany(Participant::class, ParticipantLike::class, 'likeable_id', 'participant_id')
            ->where('likeable_type', Message::class);
    }

    public function isLikedBy(Participant $participant): bool
    {
        return $this->likedByParticipants()->where('participant_id', $participant->id)->exists();
    }

    public function scopeText($query)
    {
        return $query->whereContentType('text');
    }

    public function scopeLike($query)
    {
        return $query->whereContentType('like');
    }

    public function scopeImage($query)
    {
        return $query->whereContentType('image');
    }
}
