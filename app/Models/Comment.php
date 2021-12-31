<?php

namespace App\Models;

use App\Notifications\CommentNotification;
use App\Traits\FormattedTimestamps;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property int|null $comment_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $likedByUsers
 * @property-read int|null $liked_by_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property-read Comment $parentComment
 * @property-read \App\Models\Post $post
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CommentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Comment|null $parent
 * @property-read string $formatted_created_at
 * @property-read string $formatted_update_at
 * @property-read string $formatted_updated_at
 */
class Comment extends Model
{
    use HasFactory, FormattedTimestamps;

    protected $fillable = ['user_id', 'post_id', 'comment_id', 'body'];

    protected static function booted()
    {
        static::created(function (Comment $comment) {
            if ($comment->user->id !== $comment->post->user->id && is_null($comment->parent)) {
                $comment->post->user->notify(new CommentNotification($comment));
            }
        });

        static::deleted(function (Comment $comment) {
            $comment->post->user->notifications()
                ->where('type', CommentNotification::class)
                ->where('data->id', $comment->id)
                ->delete();
        });
    }

    public function addAuthRelatedAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            switch ($attribute) {
                case 'has_liked':
                    $this->setAttribute($attribute . '_by_auth_user', Auth::user()->hasLikedComment($this));
                    break;
                default:
                    break;
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_id')->latest();
    }

    public function children()
    {
        return $this->comments()->with(['user', 'children']);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likeAs(User $user): Like
    {
        return $this->likes()->firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * @param User $user
     * @return Like|null
     */
    public function unlikeAs(User $user)
    {
        $like = $this->likes()->firstWhere('user_id', $user->id);

        if (!is_null($like)) {
            $like->delete();
        }

        return $like;
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes', 'likeable_id', 'user_id')
            ->where('likeable_type', Comment::class);
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likedByUsers()->where('user_id', $user->id)->exists();
    }

    public function delete()
    {
        $this->likes()->delete();

        return parent::delete();
    }
}
