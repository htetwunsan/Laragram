<?php

namespace App\Models;

use App\Traits\FormattedTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;


/**
 * App\Models\Room
 *
 * @property int $id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $formatted_created_at
 * @property-read string $formatted_updated_at
 * @property-read \App\Models\Message|null $latestMessage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Participant[] $participants
 * @property-read int|null $participants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Room direct()
 * @method static \Illuminate\Database\Eloquent\Builder|Room group($size = 3)
 * @method static \Illuminate\Database\Eloquent\Builder|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room solo()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $name
 * @property-read mixed $is_direct
 * @property-read mixed $is_group
 * @property-read mixed $is_solo
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereName($value)
 */
class Room extends Model
{
    use HasFactory, FormattedTimestamps;

    protected $fillable = ['type', 'name'];

    protected $appends = ['formatted_created_at', 'formatted_updated_at'];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
        // so complex
        // return $this->belongsToMany(Message::class, Participant::class, 'room_id', 'id', 'id', 'participant_id');
    }

    // public function latestMessage()
    // {
    //     return $this->hasOne(Message::class)->ofMany([
    //         'created_at' => 'max',
    //         'id' => 'max'
    //     ]);
    // }

    public function latestMessageOfParticipant(Participant $participant)
    {
        return $participant->roomMessagesNotMineNotDeleted()->latest()->latest('id')->with('participant')->first();
    }

    public function scopeSolo($query)
    {
        return $query->whereType('solo');
    }

    public function scopeDirect($query)
    {
        return $query->whereType('direct');
    }

    public function scopeGroup($query, $size = 3)
    {
        if ($size < 1 || $size > 10) {
            throw new Exception('Group size should be between 1 and 10');
        }
        return $query->whereType('group')->has('participants', '=', $size);
    }

    public function getIsSoloAttribute()
    {
        return $this->type == 'solo';
    }

    public function getIsDirectAttribute()
    {
        return $this->type == 'direct';
    }

    public function getIsGroupAttribute()
    {
        return $this->type == 'group';
    }
}
