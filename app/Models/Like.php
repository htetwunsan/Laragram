<?php

namespace App\Models;

use App\Notifications\LikeNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Like
 *
 * @property int $id
 * @property int $user_id
 * @property int $likeable_id
 * @property string $likeable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $likeable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like query()
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUserId($value)
 * @mixin \Eloquent
 */
class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'likeable_id', 'likeable_type'];

    protected static function booted()
    {
        static::created(function (Like $like) {
            if ($like->user->id !== auth()->id()) {
                $like->likeable->user->notify(new LikeNotification($like));
            }
        });

        static::deleted(function (Like $like) {
            $like->likeable->user->notifications()
                ->where('type', LikeNotification::class)
                ->where('data->id', $like->id)
                ->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
     * Get (post or comment) model
    */
    public function likeable()
    {
        return $this->morphTo();
    }
}
