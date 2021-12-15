<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Notifications\CommentNotification;
use App\Notifications\LikeNotification;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function seedTestData()
    {
        User::factory(5)
            ->hasPosts(1)
            ->create();
    }

    public function createComment($user, $post, $count = null, $comment = null)
    {
        $attributes = [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'comment_id' => is_null($comment) ? null : $comment->id
        ];
        if ($count) {
            return Comment::factory($count)->create($attributes);
        }
        return Comment::factory()->createOne($attributes);
    }

    public function findComments($user, $post, $comment = null)
    {
        if (is_null($comment)) {
            return Comment::whereUserId($user->id)
                ->wherePostId($post->id)->get();
        }
        return Comment::whereUserId($user->id)
            ->wherePostId($post->id)->whereCommentId($comment->id)->get();
    }

    public function findPostUserLiked($user, $post)
    {
        return Like::whereUserId($user->id)
            ->whereLikeableId($post->id)
            ->whereLikeableType(Post::class)
            ->get();
    }


    public function test_user_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::factory()->for($user)->create();

        self::assertEquals($user, $post->user);
    }

    public function test_images_return_correct_relation()
    {
        $this->seedTestData();

        $post = Post::find(1);

        self::assertInstanceOf(Collection::class, $post->images);

        $post->images()->create([
            'image' => $this->faker->imageUrl()
        ]);

        $post->refresh();

        self::assertCount(1, $post->images);

        $post->images()->createMany([
            ['image' => $this->faker->imageUrl()],
            ['image' => $this->faker->imageUrl()]
        ]);

        $post->refresh();

        self::assertCount(3, $post->images);
    }

    public function test_comments_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);
        $post = Post::find(1);

        self::assertInstanceOf(Collection::class, $post->comments);
        self::assertCount(0, $post->comments);

        $comment = $post->comments()->create([
            'user_id' => $user->id, 'body' => 'Test'
        ]);

        $post->refresh();

        self::assertTrue($post->comments->contains($comment));
        self::assertCount(1, $post->comments);

        $comments = $post->comments()->createMany([
            ['user_id' => $user->id, 'body' => 'Test'],
            ['user_id' => $user->id, 'body' => 'Test'],
            ['user_id' => $user->id, 'body' => 'Test'],
            ['user_id' => $user->id, 'body' => 'Test']
        ]);

        $post->refresh();

        foreach ($comments as $comment) {
            self::assertTrue($post->comments->contains($comment));
        }

        self::assertCount(5, $post->comments);
    }

    public function test_root_comments_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        self::assertInstanceOf(Collection::class, $post->rootComments);

        $rootComment = $post->rootComments()->create([
            'user_id' => $user->id, 'body' => 'Test'
        ]);

        $post->refresh();

        self::assertTrue($post->rootComments->contains($rootComment));
        self::assertCount(1, $post->rootComments);

        $rootComments = $post->rootComments()->createMany([
            ['user_id' => $user->id, 'body' => 'Test'],
            ['user_id' => $user->id, 'body' => 'Test'],
        ]);

        $post->refresh();

        self::assertTrue($post->rootComments->contains($rootComments[0]));
        self::assertTrue($post->rootComments->contains($rootComments[1]));
        self::assertCount(3, $post->rootComments);

        $childComment = $post->rootComments()->create([
            'user_id' => $user->id, 'body' => 'Test', 'comment_id' => $rootComment->id
        ]);

        self::assertNotTrue($post->rootComments->contains($childComment));
        self::assertCount(3, $post->rootComments);
    }

    public function test_comments_with_children_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        self::assertInstanceOf(Collection::class, $post->commentsWithChildren);

        $firstComment = $this->createComment($user, $post);
        $secondComment = $this->createComment($user, $post, null, Comment::find($firstComment->id));
        $thirdComment = $this->createComment($user, $post, null, Comment::find($secondComment->id));
        $fourthComment = $this->createComment($user, $post, null, Comment::find($thirdComment->id));
        $fifthComment = $this->createComment($user, $post, null, Comment::find($fourthComment->id));

        $post->refresh();

        $depth = 0;

        $recursive = function ($items) use (&$depth, &$recursive) {
            self::assertInstanceOf(Collection::class, $items);
            $depth++;

            if ($items->first()->children->count() == 0) {
                return;
            }
            $recursive($items->first()->children);
        };

        $recursive($post->commentsWithChildren);
        self::assertEquals(5, $depth);
    }

    public function test_create_comment_create_correct_relation()
    {
        $this->seedTestData();

        $firstUser = User::find(1);
        $secondUser = User::find(2);
        $firstUserPost = $firstUser->posts()->first();

        $comment = $firstUserPost->createComment($firstUser, 'Hello');
        self::assertInstanceOf(Comment::class, $comment);
        self::assertCount(1, $this->findComments($firstUser, $firstUserPost));
        self::assertCount(0, $comment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $comment->id)->get()
        );

        $comment = $firstUserPost->createComment($firstUser, 'Hello');
        self::assertInstanceOf(Comment::class, $comment);
        self::assertCount(2, $this->findComments($firstUser, $firstUserPost));
        self::assertCount(0, $comment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $comment->id)->get()
        );

        $comment = $firstUserPost->createComment($secondUser, 'Hello');
        self::assertInstanceOf(Comment::class, $comment);
        self::assertCount(1, $this->findComments($secondUser, $firstUserPost));
        self::assertCount(1, $comment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $comment->id)->get()
        );

        self::assertCount(3, Comment::wherePostId($firstUserPost->id)->get());
        $this->assertDatabaseCount('comments', 3);

        $parentComment = Comment::find(1);

        $comment = $firstUserPost->createComment($firstUser, 'Hello', $parentComment);

        self::assertInstanceOf(Comment::class, $comment);
        self::assertCount(1, $this->findComments($firstUser, $firstUserPost, $parentComment));
        self::assertCount(4, Comment::wherePostId($firstUserPost->id)->get());
        $this->assertDatabaseCount('comments', 4);
    }


    public function test_likes_return_correct_relation()
    {
        $this->seedTestData();

        $users = Collection::make([1, 2, 3])->map(function ($i) {
            return User::find($i);
        });

        $post = Post::find(1);

        self::assertInstanceOf(Collection::class, $post->likes);

        $like = $post->likes()->create([
            'user_id' => $users[0]->id
        ]);

        $post->refresh();

        self::assertTrue($post->likes->contains($like));
        self::assertCount(1, $post->likes);

        $likes = $post->likes()->createMany([
            ['user_id' => $users[1]->id],
            ['user_id' => $users[2]->id]
        ]);

        $post->refresh();

        self::assertTrue($post->likes->contains($likes[0]));
        self::assertTrue($post->likes->contains($likes[1]));
        self::assertCount(3, $post->likes);
    }

    public function test_like_as_create_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        $like = $post->likeAs($user);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $post->likeAs($user);

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

        $post = Post::find(1);

        $like = Like::create([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class
        ]);

        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $post->unlikeAs($user);

        self::assertInstanceOf(Like::class, $like);
        self::assertNull(Like::find($like->id));
        self::assertCount(0, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $post->unlikeAs($user);

        self::assertNull($like);
    }

    public function test_liked_by_users_return_correct_relation()
    {
        $this->seedTestData();

        $user1 = User::find(1);
        $user2 = User::find(2);

        $post = Post::find(1);

        self::assertInstanceOf(Collection::class, $post->likedByUsers);

        $post->likedByUsers()->attach($user1, ['likeable_type' => Post::class]);

        $post->refresh();

        self::assertTrue($post->likedByUsers->contains($user1));
        self::assertCount(1, $post->likedByUsers);

        $post->likedByUsers()->attach($user2, ['likeable_type' => Post::class]);

        $post->refresh();

        self::assertTrue($post->likedByUsers->contains($user2));
        self::assertCount(2, $post->likedByUsers);
    }

    public function test_is_liked_by_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        self::assertFalse($post->isLikedBy($user));

        Like::create([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class
        ]);

        self::assertTrue($post->isLikedBy($user));
    }

    public function test_delete_method_also_delete_related_likes()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        $like = Like::create([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class
        ]);

        self::assertTrue($this->findPostUserLiked($user, $post)->contains($like));
        self::assertCount(1, $this->findPostUserLiked($user, $post));

        $post->delete();

        self::assertFalse($this->findPostUserLiked($user, $post)->contains($like));
        self::assertCount(0, $this->findPostUserLiked($user, $post));
    }

}
