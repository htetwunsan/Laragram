<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\LikeNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function seedTestData()
    {
        User::factory(5)->has(Post::factory())->create();

        $user = User::find(1);

        $post = Post::find(1);

        Comment::factory(5)->for($user)->for($post)->create();
    }

    public function likeComment($user, $comment)
    {
        return Like::create([
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class
        ]);
    }

    public function findLike($user, $comment)
    {
        return Like::whereUserId($user->id)
            ->whereLikeableId($comment->id)
            ->whereLikeableType(Comment::class)
            ->get();
    }

    public function test_user_return_correct_relation()
    {
        $this->seedTestData();

        $comment = Comment::find(1);

        self::assertInstanceOf(User::class, $comment->user);
    }

    public function test_post_return_correct_relation()
    {
        $this->seedTestData();

        $comment = Comment::find(1);

        self::assertInstanceOf(Post::class, $comment->post);
    }

    public function test_parent_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        $parent = Comment::find(1);

        $child = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'comment_id' => $parent->id
        ]);

        self::assertInstanceOf(Comment::class, $child->parent);
        self::assertEquals($parent, $child->parent);
    }

    public function test_comments_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        $comment = Comment::find(1);

        self::assertInstanceOf(Collection::class, $comment->comments);
        self::assertCount(0, $comment->comments);

        $children = Comment::factory(5)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'comment_id' => $comment->id
        ]);

        $comment->refresh();

        self::assertCount(5, $comment->comments);
        $children->each(function ($child) use ($comment) {
            self::assertTrue($comment->comments->contains($child));
        });
    }

    public function test_children_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        $parent = Comment::find(1);

        self::assertInstanceOf(Collection::class, $parent->children);
        self::assertCount(0, $parent->children);

        $maxDepth = 0;

        $currentParentId = $parent->id;

        for ($i = 0; $i < 5; ++$i) {
            $c = Comment::factory()->create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'comment_id' => $currentParentId
            ]);

            $currentParentId = $c->id;
        }

        $recursive = function ($item, $depth = 0) use (&$maxDepth, &$recursive) {

            if ($item->children->count() <= 0) {
                $maxDepth = max($maxDepth, $depth);
                return;
            }

            foreach ($item->children as $child) {
                $recursive($child, $depth + 1);
            }
        };
        $parent->refresh();
        $recursive($parent);

        self::assertEquals(5, $maxDepth);

        $currentParentId = $parent->id;

        for ($i = 0; $i < 10; ++$i) {
            $c = Comment::factory()->create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'comment_id' => $currentParentId
            ]);

            $currentParentId = $c->id;
        }
        $parent->refresh();
        $recursive($parent);

        self::assertEquals(10, $maxDepth);
    }

    public function test_likes_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        $comment = Comment::find(1);

        self::assertInstanceOf(Collection::class, $comment->likes);

        $like = $comment->likes()->create(['user_id' => $user->id, 'post_id' => $post->id]);

        $comment->refresh();

        self::assertTrue($comment->likes->contains($like));
        self::assertCount(1, $comment->likes);

        $likes = $comment->likes()->createMany([
            ['user_id' => 2, 'post_id' => $post->id],
            ['user_id' => 3, 'post_id' => $post->id],
            ['user_id' => 4, 'post_id' => $post->id],
            ['user_id' => 5, 'post_id' => $post->id],
        ]);

        $comment->refresh();

        $likes->each(function ($like) use ($comment) {
            self::assertTrue($comment->likes->contains($like));
        });

        self::assertCount(5, $comment->likes);
    }

    public function test_like_as_create_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $comment = Comment::find(1);

        $like = $comment->likeAs($user);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $comment->likeAs($user);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());
    }

    public function test_unlike_as_delete_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $comment = Comment::find(1);

        $like = Like::create([
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class
        ]);

        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $comment->unlikeAs($user);

        self::assertInstanceOf(Like::class, $like);
        self::assertNull(Like::find($like->id));
        self::assertCount(0, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $comment->unlikeAs($user);

        self::assertNull($like);
    }

    public function test_liked_by_users_return_correct_relation()
    {
        $this->seedTestData();

        $users = Collection::make([1, 2, 3])->map(function ($i) {
            return User::find($i);
        });

        $comment = Comment::find(1);

        self::assertInstanceOf(Collection::class, $comment->likedByUsers);

        $comment->likedByUsers()->attach($users[0], ['likeable_type' => Comment::class]);

        $comment->refresh();

        self::assertTrue($comment->likedByUsers->contains($users[0]));
        self::assertCount(1, $comment->likedByUsers);

        $comment->likedByUsers()->attach($users[1], ['likeable_type' => Comment::class]);
        $comment->likedByUsers()->attach($users[2], ['likeable_type' => Comment::class]);

        $comment->refresh();

        self::assertTrue($comment->likedByUsers->contains($users[1]));
        self::assertTrue($comment->likedByUsers->contains($users[2]));
        self::assertCount(3, $comment->likedByUsers);
    }

    public function test_is_liked_by_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $comment = Comment::find(1);

        self::assertFalse($comment->isLikedBy($user));

        $this->likeComment($user, $comment);

        self::assertTrue($comment->isLikedBy($user));
    }

    public function test_delete_method_also_delete_children()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        $parent = Comment::find(1);

        self::assertInstanceOf(Collection::class, $parent->children);
        self::assertCount(0, $parent->children);

        $comments = [$parent->unsetRelation('children')];

        for ($i = 0, $currentParentId = $parent->id; $i < 5; ++$i) {
            $c = Comment::factory()->create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'comment_id' => $currentParentId
            ]);

            array_push($comments, $c);

            $currentParentId = $c->id;
        }

        $parent->delete();

        foreach ($comments as $comment) {
            self::assertNull(Comment::find($comment->id));
        }
    }

    public function test_delete_method_also_delete_related_likes()
    {
        $this->seedTestData();

        $user = User::find(1);

        $comment = Comment::find(1);

        $like = Like::create([
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class
        ]);

        self::assertTrue($this->findLike($user, $comment)->contains($like));
        self::assertCount(1, $this->findLike($user, $comment));

        $comment->delete();

        self::assertFalse($this->findLike($user, $comment)->contains($like));
        self::assertCount(0, $this->findLike($user, $comment));
    }
}





