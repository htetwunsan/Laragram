<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * App\Models\PostImage
 *
 * @property int $id
 * @property int $post_id
 * @property string $image
 * @property string|null $alternate_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post $post
 * @method static \Database\Factories\PostImageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereAlternateText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostImage extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'alternate_text'];

    protected static function booted()
    {
        static::deleted(function (PostImage $postImage) {
            Storage::disk('public')->delete($postImage->image);
        });
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
