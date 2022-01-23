<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * App\Models\Blocking
 *
 * @property int $id
 * @property int $blocking_id
 * @property int $blocker_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $follower
 * @property-read \App\Models\User $following
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking whereBlockerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking whereBlockingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocking whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $blocker
 * @property-read \App\Models\User $blocking
 */
class Blocking extends Model
{
    use HasFactory;

    protected $fillable = ['blocker_id', 'blocking_id'];

    protected static function booted()
    {
        static::creating(function (Blocking $blocking) {
            if ($blocking->blocker_id === $blocking->blocking_id) {
                throw ValidationException::withMessages(['nonField' => 'You cannot block yourself.']);
            }
        });

        static::created(function (Blocking $blocking) {
            $blocking->blocker->unfollowEachOther($blocking->blocking);
        });

        static::deleting(function (Blocking $blocking) {
            if ($blocking->blocker_id === $blocking->blocking_id) {
                throw ValidationException::withMessages(['nonField' => 'You cannot unblock yourself.']);
            }
        });
    }


    public function blocking()
    {
        return $this->belongsTo(User::class, 'blocking_id');
    }

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocker_id');
    }
}
