<?php

namespace App\Models;

use App\Traits\FormattedTimestamps;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $caption
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PostImage[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $likedByUsers
 * @property-read int|null $liked_by_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @mixin \Eloquent
 * @property-read string $formatted_created_at
 * @property-read string $formatted_update_at
 * @property-read \App\Models\PostImage|null $image
 * @property-read string $formatted_updated_at
 */
class Post extends Model
{
    use HasFactory, FormattedTimestamps;

    protected $fillable = ['caption'];

    public function addAuthRelatedAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            switch ($attribute) {
                case 'has_liked':
                    $this->setAttribute($attribute . '_by_auth_user', Auth::user()->hasLikedPost($this));
                    break;
                case 'has_saved':
                    $this->setAttribute($attribute . '_by_auth_user', Auth::user()->hasSavedPost($this));
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

    public function image()
    {
        return $this->hasOne(PostImage::class)->oldestOfMany();
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function createImage($postImage, $alternateText)
    {
        //        $image = Image::make($postImage)->fit(1080, 1080)->encode('jpg', 80);
        //
        //        $imagePath = 'post/images/' . explode('.', $postImage->hashName())[0] . '.jpg';
        //
        //        Storage::disk('public')->put($imagePath, $image, 'public');

        $imagePath = Cloudinary::upload($postImage->getRealPath(), [
            'width' => 1080,
            'height' => 1080,
            'crop' => 'fit',
        ])->getSecurePath();

        $this->images()->create(['image' => $imagePath, 'alternate_text' => $alternateText]);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function rootComments()
    {
        return $this->comments()->whereNull('comment_id')->latest();
    }

    public function commentsWithChildren()
    {
        return $this->rootComments()->with('children');
    }

    public function createComment(User $user, $body, Comment $parent = null): Comment
    {
        return $this->comments()->create([
            'user_id' => $user->id,
            'body' => $body,
            'comment_id' => is_null($parent) ? null : $parent->id
        ]);
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
            ->where('likeable_type', Post::class);
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
