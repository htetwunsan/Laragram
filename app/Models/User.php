<?php

namespace App\Models;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Str;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $name
 * @property string $birthday
 * @property string $password
 * @property string|null $profile_image
 * @property string|null $website
 * @property string|null $bio
 * @property string|null $phone
 * @property string $gender
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|User[] $blockers
 * @property-read int|null $blockers_count
 * @property-read Collection|User[] $blockings
 * @property-read int|null $blockings_count
 * @property-read Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Collection|User[] $followers
 * @property-read int|null $followers_count
 * @property-read Collection|User[] $followings
 * @property-read int|null $followings_count
 * @property-read Collection|\App\Models\Comment[] $likedComments
 * @property-read int|null $liked_comments_count
 * @property-read Collection|\App\Models\Post[] $likedPosts
 * @property-read int|null $liked_posts_count
 * @property-read Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|\App\Models\PostImage[] $postImages
 * @property-read int|null $post_images_count
 * @property-read Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read Collection|\App\Models\Post[] $savedPosts
 * @property-read int|null $saved_posts_count
 * @property-read Collection|\App\Models\Save[] $saves
 * @property-read int|null $saves_count
 * @property-read Collection|\App\Models\Story[] $stories
 * @property-read int|null $stories_count
 * @property-read Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection|\App\Models\Story[] $viewedStories
 * @property-read int|null $viewed_stories_count
 * @property-read Collection|\App\Models\View[] $views
 * @property-read int|null $views_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWebsite($value)
 * @mixin \Eloquent
 * @property string|null $facebook_id
 * @property string|null $facebook_token
 * @property string|null $facebook_refresh_token
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookToken($value)
 * @property-read Collection|\App\Models\Room[] $rooms
 * @property-read int|null $rooms_count
 * @property-read Collection|\App\Models\Participant[] $participants
 * @property-read int|null $participants_count
 * @property-read Collection|\App\Models\Room[] $deletedRooms
 * @property-read int|null $deleted_rooms_count
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'username',
        'name',
        'birthday',
        'password',
        'profile_image',
        'website',
        'bio',
        'phone',
        'gender',
        'facebook_id',
        'facebook_token',
        'facebook_refresh_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'facebook_id',
        'facebook_token',
        'facebook_refresh_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (User $user) {
            $username = explode('@', $user->email)[0];

            if (!empty($username)) {
                if (User::whereUsername($username)->exists()) {
                    $username .= now()->microsecond;
                }

                $user->username = $username;
            } else if (!empty($user->name)) {
                $username = $user->name;

                if (User::whereUsername($username)->exists()) {
                    $username .= now()->microsecond;
                }

                $user->username = $username;
            } else {
                $username = (string) Str::uuid();

                $user->username = $username;
            }
        });

        static::deleted(function (User $user) {
            if (!is_null($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
        });
    }

    //    public function getProfileImageForWebAttribute()
    //    {
    //        return str_replace('upload/', 'upload/w_320,h_320,c_fit/', $this->profile_image);
    //    }

    public function addProfileImage($profileImage)
    {
        //        $image = Image::make($profileImage)->fit(320, 320)->encode('jpg', 80);

        //        $imagePath = 'profile_images/' . explode('.', $profileImage->hashName())[0] . '.jpg';
        //
        //        Storage::disk('public')->put($imagePath, $image, 'public');

        $imagePath = Cloudinary::upload($profileImage->getRealPath(), [
            'width' => 320,
            'height' => 320,
            'crop' => 'fit',
        ])->getSecurePath();

        return $this->update(['profile_image' => $imagePath]);
    }

    public function removeProfileImage(): bool
    {
        return $this->update(['profile_image' => null]);
    }

    public function followingsUserIds()
    {
        if ($this->relationLoaded('followings')) {
            return $this->followings->pluck('id');
        }
        return $this->followings()->pluck('users.id');
    }

    public function blockingsUserIds()
    {
        if ($this->relationLoaded('blockings')) {
            return $this->blockings->pluck('id');
        }
        return $this->blockings()->pluck('users.id');
    }

    public function blockersUserIds()
    {
        if ($this->relationLoaded('blockings')) {
            return $this->blockers->pluck('id');
        }
        return $this->blockers()->pluck('users.id');
    }

    public function recommendedPosts()
    {
        $in = $this->followingsUserIds()->merge($this->id);
        return Post::whereIn('user_id', $in);
    }

    public function recommendedStories()
    {
        return User::whereHas('activeStories')->with('activeStories')
            ->whereIn('id', $this->followingsUserIds());
    }

    public function exploreUsers()
    {
        $notIn = collect($this->id)
            ->merge($this->blockingsUserIds())
            ->merge($this->followingsUserIds());

        return User::whereNotIn('id', $notIn);
    }

    public function explorePosts()
    {
        $notIn = collect($this->id)
            ->merge($this->blockingsUserIds());

        return Post::has('images')->with('images')->whereNotIn('user_id', $notIn);
    }

    public function addAuthRelatedAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            switch ($attribute) {
                case 'is_blocked':
                    $this->setAttribute($attribute . '_by_auth_user', Auth::user()->isBlocking($this));
                    break;
                case 'is_blocking':
                    $this->setAttribute($attribute . '_auth_user', Auth::user()->isBlockedBy($this));
                    break;
                case 'is_followed':
                    $this->setAttribute($attribute . '_by_auth_user', Auth::user()->isFollowing($this));
                    break;
                case 'is_following':
                    $this->setAttribute($attribute . '_auth_user', Auth::user()->isFollowedBy($this));
                    break;
                case 'stories_have_viewed':
                    $this->setAttribute($attribute . '_by_auth_user', Auth::user()->hasViewedStories($this->activeStories));
            }
        }
    }

    /* followings start */

    public function followings()
    {
        return $this->belongsToMany(User::class, Following::class, 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, Following::class, 'following_id', 'follower_id');
    }

    public function isFollowing(User $user): bool
    {
        if ($this->is($user)) {
            return true;
        }

        if ($this->relationLoaded('followings')) {
            return $this->followings->contains($user);
        }
        return Following::whereFollowerId($this->id)->whereFollowingId($user->id)->exists();
    }

    public function isFollowedBy(User $user): bool
    {
        if ($this->is($user)) {
            return true;
        }

        if ($this->relationLoaded('followers')) {
            return $this->followers->contains($user);
        }
        return Following::whereFollowerId($user->id)->whereFollowingId($this->id)->exists();
    }

    public function areFollowingEachOther(User $user): bool
    {
        return $this->isFollowing($user) && $this->isFollowedBy($user);
    }

    public function follow(User $user): Following
    {
        return Following::firstOrCreate([
            'follower_id' => $this->id,
            'following_id' => $user->id
        ]);
    }

    /**
     * @param User $user
     * @return Following|\Illuminate\Database\Eloquent\Model|null
     */
    public function unfollow(User $user)
    {
        $f = Following::firstWhere([
            'follower_id' => $this->id,
            'following_id' => $user->id
        ]);

        if (!is_null($f)) {
            $f->delete();
        }

        return $f;
    }

    public function removeFollower(User $user)
    {
        $f = Following::firstWhere([
            'follower_id' => $user->id,
            'following_id' => $this->id
        ]);

        if (!is_null($f)) {
            $f->delete();
        }

        return $f;
    }

    public function unfollowEachOther(User $user): array
    {
        return [
            $this->unfollow($user),
            $user->unfollow($this)
        ];
    }


    //    public function toggleFollow(User $user): array
    //    {
    //        return $this->followings()->toggle($user);
    //    }

    /* followings end */

    /* like start */

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, Like::class, 'user_id', 'likeable_id')
            ->where('likeable_type', Post::class);
    }

    public function hasLikedPost(Post $post): bool
    {
        if ($this->relationLoaded('likedPosts')) {
            return $this->likedPosts->contains($post);
        }

        return $this->likes()
            ->where('likeable_id', $post->id)
            ->where('likeable_type', Post::class)
            ->exists();
    }

    public function likePost(Post $post): Like
    {
        return $post->likeAs($this);
    }


    /**
     * @param Post $post
     * @return Like|null
     */
    public function unlikePost(Post $post)
    {
        return $post->unlikeAs($this);
    }

    public function likedComments()
    {
        return $this->belongsToMany(Comment::class, Like::class, 'user_id', 'likeable_id')
            ->where('likeable_type', Comment::class);
    }

    public function hasLikedComment(Comment $comment): bool
    {
        if ($this->relationLoaded('likedComments')) {
            return $this->likedComments->contains($comment);
        }

        return $this->likes()
            ->where('likeable_id', $comment->id)
            ->where('likeable_type', Comment::class)
            ->exists();
    }

    public function likeComment(Comment $comment): Like
    {
        return $comment->likeAs($this);
    }


    /**
     * @param Comment $comment
     * @return Like|null
     */
    public function unlikeComment(Comment $comment)
    {
        return $comment->unlikeAs($this);
    }

    /* like end */

    /* post start */

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function createPost($caption, $postImage, $alternateText)
    {
        $post = $this->posts()->create(['caption' => $caption]);
        $post->createImage($postImage, $alternateText);
        return $post;
    }

    public function postImages()
    {
        return $this->hasManyThrough(PostImage::class, Post::class);
    }

    public function latestPostImages()
    {
        return $this->postImages()->latest()->take(3);
    }

    /* post end */

    /* comment start */

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentPost(Post $post, $body, Comment $parent = null)
    {
        return $post->createComment($this, $body, $parent);
    }

    /* comment end */

    /* saved start */

    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, Save::class, 'user_id', 'post_id');
    }

    public function hasSavedPost(Post $post)
    {
        if ($this->relationLoaded('savedPosts')) {
            return $this->savedPosts->contains($post);
        }
        return $this->saves()->where('post_id', $post->id)->exists();
    }

    public function savePost(Post $post): bool
    {
        if ($this->hasSavedPost($post)) return true;

        $this->savedPosts()->attach($post);
        return true;
    }

    public function unsavePost(Post $post): bool
    {
        return $this->savedPosts()->detach($post);
    }

    /* saved end */

    /* story start */

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function activeStories()
    {
        return $this->stories()->where('created_at', '>=', now()->subDay());
    }

    public function createStory($storyImage)
    {
        //        $image = Image::make($storyImage)->fit(1080, 1080)->encode('jpg', 80);
        //
        //        $imagePath = 'story/images/' . explode('.', $storyImage->hashName())[0] . '.jpg';
        //
        //        Storage::disk('public')->put($imagePath, $image, 'public');

        $imagePath = Cloudinary::upload($storyImage->getRealPath(), [
            'width' => 1080,
            'height' => 1080,
            'crop' => 'fit',
        ])->getSecurePath();

        return $this->stories()->create([
            'image' => $imagePath
        ]);
    }

    /* story end */

    /* view start */

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function viewedStories()
    {
        return $this->belongsToMany(Story::class, View::class, 'user_id', 'viewable_id')
            ->where('viewable_type', Story::class);
    }

    public function hasViewedStory(Story $story): bool
    {
        if ($this->relationLoaded('viewedStories')) {
            return $this->viewedStories->contains($story);
        }
        return $this->views()
            ->where('viewable_id', $story->id)
            ->where('viewable_type', Story::class)
            ->exists();
    }

    public function hasViewedStories(Collection $stories): bool
    {
        $has_viewed = true;
        $stories->each(function (Story $story) use (&$has_viewed) {
            $has_viewed = $this->hasViewedStory($story);
            return $has_viewed;
        });

        return $has_viewed;
    }

    public function viewStory(Story $story): View
    {
        return $story->viewAs($this);
    }


    public function removeViewStory(Story $story): bool
    {
        return $story->removeViewAs($this);
    }
    /* view end */

    /* blocking  start */
    public function blockings()
    {
        return $this->belongsToMany(User::class, Blocking::class, 'blocker_id', 'blocking_id')
            ->whereNotIn('users.id', [$this->id]);
    }

    public function blockers()
    {
        return $this->belongsToMany(User::class, Blocking::class, 'blocking_id', 'blocker_id')
            ->whereNotIn('users.id', [$this->id]);
    }

    public function isBlocking(User $user): bool
    {
        if ($this->is($user)) {
            return false;
        }

        if ($this->relationLoaded('blockings')) {
            return $this->blockings->contains($user);
        }
        return $this->blockings()->where('blocking_id', $user->id)->exists();
    }

    public function isBlockedBy(User $user): bool
    {
        if ($this->is($user)) {
            return false;
        }

        if ($this->relationLoaded('blockers')) {
            return $this->blockers->contains($user);
        }
        return $this->blockers()->where('blocker_id', $user->id)->exists();
    }

    public function block(User $user): Blocking
    {
        return Blocking::firstOrCreate([
            'blocker_id' => $this->id,
            'blocking_id' => $user->id
        ]);
    }

    /**
     * @param User $user
     * @return Blocking|\Illuminate\Database\Eloquent\Model|null
     */
    public function unblock(User $user)
    {
        $b = Blocking::firstWhere([
            'blocker_id' => $this->id,
            'blocking_id' => $user->id
        ]);

        if (!is_null($b)) {
            $b->delete();
        }

        return $b;
    }
    /* blocking end */

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function isParticipantOf(Room $room): bool
    {
        return $this->participants()->whereRoomId($room->id)->exists();
    }

    public function becomeParticipantOf(Room $room): Participant
    {
        return $this->participants()->whereRoomId($room->id)->first();
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, Participant::class, 'user_id', 'room_id')
            ->withPivot('room_deleted_at')
            ->wherePivotNull('room_deleted_at')
            ->withTimestamps();
    }

    public function deletedRooms()
    {
        return $this->belongsToMany(Room::class, Participant::class, 'user_id', 'room_id')
            ->withPivot('room_deleted_at')
            ->wherePivotNotNull('room_deleted_at')
            ->withTimestamps();
    }

    // public function rooms(): BelongsToMany
    // {
    //     return $this->belongsToMany(Room::class, Participant::class, 'user_id', 'room_id')
    //         ->withPivot('room_deleted_at')
    //         ->wherePivotNull('room_deleted_at');
    // }

    // public function deletedRooms(): BelongsToMany
    // {
    //     return $this->belongsToMany(Room::class, Participant::class, 'user_id', 'room_id')
    //         ->withPivot('room_deleted_at')
    //         ->wherePivotNotNull('room_deleted_at');
    // }
}
