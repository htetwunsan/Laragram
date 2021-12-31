<?php

namespace App\Models;

use App\Notifications\FollowNotification;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * App\Models\Following
 *
 * @property int $id
 * @property int $following_id
 * @property int $follower_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $follower
 * @property-read \App\Models\User $following
 * @method static \Illuminate\Database\Eloquent\Builder|Following newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Following newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Following query()
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereFollowerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereFollowingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Following whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Following extends Model
{

    protected $fillable = ['follower_id', 'following_id'];

    protected $table = 'following';

    protected static function booted()
    {
        static::creating(function (Following $f) {
            if ($f->follower_id === $f->following_id) {
                throw ValidationException::withMessages(['You cannot follow yourself.']);
            }

            $follower = $f->follower;
            $following = $f->following;

            if ($follower->isBlocking($following) || $follower->isBlockedBy($following)) {
                throw ValidationException::withMessages(['Following user does not exist.']);
            }
        });

        static::created(function (Following $f) {
            $f->following->notify(new FollowNotification($f));
        });

        self::deleting(function (Following $f) {
            if ($f->follower_id === $f->following_id) {
                throw ValidationException::withMessages(['You cannot unfollow yourself.']);
            }
        });

        static::deleted(function (Following $f) {
            $f->following->notifications()
                ->where('type', FollowNotification::class)
                ->where('data->id', $f->id)
                ->delete();
        });
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }
}
