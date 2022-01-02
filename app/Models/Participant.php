<?php

namespace App\Models;

use Cloudinary;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/**
 * App\Models\Participant
 *
 * @property int $id
 * @property int $room_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $room_deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DeletedMessage[] $deletedMessages
 * @property-read int|null $deleted_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DeletedMessage[] $deletedRoomMessages
 * @property-read int|null $deleted_room_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $deletedRooms
 * @property-read int|null $deleted_rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\Room $room
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $roomMessages
 * @property-read int|null $room_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 * @property-read int|null $rooms_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereRoomDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SeenMessage[] $seenMessages
 * @property-read int|null $seen_messages_count
 * @property-read Collection|\App\Models\Message[] $roomMessagesNotDeleted
 * @property-read int|null $room_messages_not_deleted_count
 * @property-read Collection|\App\Models\Message[] $roomMessagesNotMine
 * @property-read int|null $room_messages_not_mine_count
 * @property-read Collection|\App\Models\Message[] $roomMessagesNotMineNotDeleted
 * @property-read int|null $room_messages_not_mine_not_deleted_count
 * @property string|null $room_muted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereRoomMutedAt($value)
 */
class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'room_id', 'room_deleted_at', 'room_muted_at'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, Participant::class, 'user_id', 'room_id', 'user_id')
            ->withPivot('room_deleted_at')
            ->wherePivotNull('room_deleted_at');;
    }

    public function deletedRooms()
    {
        return $this->belongsToMany(Room::class, Participant::class, 'user_id', 'room_id', 'user_id')
            ->withPivot('room_deleted_at')
            ->wherePivotNotNull('room_deleted_at');;
    }

    public function deleteRoom()
    {
        $this->roomMessagesNotDeleted->each(function ($message) {
            $this->deleteMessage($message);
        });
        $this->room_deleted_at = $this->freshTimestamp();
        return $this->save();
    }

    public function restoreRoom()
    {
        $this->room_deleted_at = null;
        return $this->save();
    }

    public function muteRoom()
    {
        $this->room_muted_at = $this->freshTimestamp();
        return $this->save();
    }

    public function unmuteRoom()
    {
        $this->room_muted_at = null;
        return $this->save();
    }

    // public function canJoinRoom(Room $room)
    // {
    //     if ($this->relationLoaded('rooms')) {
    //         return $this->rooms->contains($room);
    //     }
    //     return $this->rooms()->where('rooms.id', $room->id)->exists();
    // }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function seenMessages()
    {
        return $this->hasMany(SeenMessage::class);
    }

    public function seeMessage(Message $message)
    {
        return $this->seenMessages()->firstOrCreate(['message_id' => $message->id]);
    }

    public function seeMessages(Collection $messages)
    {
        return $messages->map(function (Message $message) {
            return $this->seeMessage($message);
        });
    }

    public function deletedMessages()
    {
        return $this->hasMany(DeletedMessage::class);
    }

    public function roomMessages()
    {
        return $this->hasMany(Message::class, 'room_id', 'room_id');
    }

    public function roomMessagesNotMine()
    {
        return $this->roomMessages()->whereNotIn('participant_id', [$this->id]);
    }

    public function roomMessagesNotDeleted()
    {
        $notIn = $this->deletedMessages()->get('message_id');
        return $this->roomMessages()->whereNotIn('id', $notIn);
    }

    public function roomMessagesNotSeen()
    {
        $notIn = $this->seenMessages()->get('message_id');
        return $this->roomMessages()->whereNotIn('id', $notIn);
    }

    public function roomMessagesNotMineNotDeleted()
    {
        $notIn = $this->deletedMessages()->get('message_id');
        return $this->roomMessages()
            ->whereNotIn('participant_id', [$this->id])
            ->whereNotIn('id', $notIn);
    }

    public function roomMessagesNotMineNotSeen()
    {
        $notIn = $this->seenMessages()->get('message_id');
        return $this->roomMessages()
            ->whereNotIn('participant_id', [$this->id])
            ->whereNotIn('id', $notIn);
    }

    public function roomMessagesNotMineNotDeletedNotSeen()
    {
        $notIn = $this->deletedMessages()->get('message_id')->merge($this->seenMessages()->get('message_id'));
        return $this->roomMessages()
            ->whereNotIn('participant_id', [$this->id])
            ->whereNotIn('id', $notIn);
    }

    public function deletedRoomMessages()
    {
        return $this->hasMany(DeletedMessage::class, 'room_id', 'room_id');
    }

    public function createMessage(string $content, string $contentType = 'text')
    {
        $this->room->participants->each(function ($participant) {
            $participant->restoreRoom();
        });
        return $this->messages()->create(['content' => $content, 'content_type' => $contentType]);
    }

    public function createTextMessage(string $content)
    {
        return $this->createMessage($content);
    }

    public function createLikeMessage()
    {
        return $this->createMessage('::Like', 'like');
    }

    public function createImageMessage(UploadedFile $file)
    {
        $path = Cloudinary::upload($file->getRealPath(), [
            'width' => 1080,
            'height' => 1080,
            'crop' => 'fit',
        ])->getSecurePath();
        return $this->createMessage($path, 'image');
    }

    public function deleteMessage(Message $message)
    {
        return $this->deletedMessages()->firstOrCreate(['message_id' => $message->id]);
    }

    public function likeMessage(Message $message)
    {
        return $message->likeAs($this);
    }

    public function unlikeMessage(Message $message)
    {
        return $message->unlikeAs($this);
    }
}
