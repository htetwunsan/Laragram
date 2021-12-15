<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Storage;

/**
 * App\Models\Story
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Story newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Story newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Story query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Story whereUserId($value)
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $viewedByUsers
 * @property-read int|null $viewed_by_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\View[] $views
 * @property-read int|null $views_count
 * @method static \Database\Factories\StoryFactory factory(...$parameters)
 */
class Story extends Model
{
    use HasFactory, Prunable;

    protected $fillable = ['user_id', 'image'];

    protected static function booted()
    {
        static::deleted(function (Story $story) {
            Storage::disk('public')->delete($story->image);
        });
    }

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subDay());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function viewAs(User $user): View
    {
        return $this->views()->firstOrCreate(['user_id' => $user->id]);
    }

    public function removeViewAs(User $user): bool
    {
        return $this->views()->where('user_id', $user->id)->delete();
    }

    public function viewedByUsers()
    {
        return $this->belongsToMany(User::class, View::Class, 'viewable_id', 'user_id')
            ->where('viewable_type', Story::class);
    }

    public function isViewedBy(User $user): bool
    {
        return $this->viewedByUsers()->where('user_id', $user->id)->exists();
    }

    public function delete()
    {

        $this->views()->delete();

        return parent::delete();
    }
}
