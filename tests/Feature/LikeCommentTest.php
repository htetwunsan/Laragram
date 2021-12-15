<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeCommentTest extends TestCase
{
    use RefreshDatabase;

    public function firstUser()
    {
        return User::find(1);
    }

    public function secondUser()
    {
        return User::find(2);
    }

    public function firstComment()
    {
        return Comment::find(1);
    }

    public function secondComment()
    {
        return Comment::find(2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        User::factory(2)->has(Post::factory())->create();
        Comment::factory()->create(['user_id' => 1, 'post_id' => 1]);
        Comment::factory()->create(['user_id' => 2, 'post_id' => 2]);
    }

    public function likeCommentAsUser($user, $comment)
    {
        return Like::create([
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class
        ]);
    }

    public function userHasLikedComment($user, $comment)
    {
        return Like::whereUserId($user->id)
            ->whereLikeableId($comment->id)->whereLikeableType(Comment::class)->count();
    }


    public function test_user_likes_return_correct_relation()
    {
        self::assertInstanceOf(Collection::class, $this->firstUser()->likes);

        // First user like first user's comment
        $like = $this->likeCommentAsUser($this->firstUser(), $this->firstComment());
        self::assertEquals(1, $this->firstUser()->likes->count());
        self::assertTrue($this->firstUser()->likes->contains($like));

        // First user like second user's comment
        $like = $this->likeCommentAsUser($this->firstUser(), $this->secondComment());
        self::assertEquals(2, $this->firstUser()->likes->count());
        self::assertTrue($this->firstUser()->likes->contains($like));
    }

    public function test_user_liked_comments_return_correct_relation()
    {
        self::assertInstanceOf(Collection::class, $this->firstUser()->likedPosts);

        // First user like first user's comment
        $this->likeCommentAsUser($this->firstUser(), $this->firstComment());
        self::assertEquals(1, $this->firstUser()->likedComments->count());
        self::assertTrue($this->firstUser()->likedComments->contains($this->firstComment()));

        // First user like second user's comment
        $this->likeCommentAsUser($this->firstUser(), $this->secondComment());
        self::assertEquals(2, $this->firstUser()->likedComments->count());
        self::assertTrue($this->firstUser()->likedComments->contains($this->secondComment()));
    }

    public function test_comment_likes_return_correct_relation()
    {
        self::assertInstanceOf(Collection::class, $this->firstComment()->likes);

        // First user like first user's comment
        $like = $this->likeCommentAsUser($this->firstUser(), $this->firstComment());
        self::assertEquals(1, $this->firstComment()->likes->count());
        self::assertTrue($this->firstComment()->likes->contains($like));

        // Second user like first user's comment
        $like = $this->likeCommentAsUser($this->secondUser(), $this->firstComment());
        self::assertEquals(2, $this->firstComment()->likes->count());
        self::assertTrue($this->firstComment()->likes->contains($like));
    }

    public function test_comment_liked_by_users_return_correct_relation()
    {
        self::assertInstanceOf(Collection::class, $this->firstComment()->likedByUsers);

        // First user like first user's comment
        $this->likeCommentAsUser($this->firstUser(), $this->firstComment());
        self::assertEquals(1, $this->firstComment()->likedByUsers->count());
        self::assertTrue($this->firstComment()->likedByUsers->contains($this->firstUser()));

        // Second user like first user's comment
        $this->likeCommentAsUser($this->secondUser(), $this->firstComment());
        self::assertEquals(2, $this->firstComment()->likedByUsers->count());
        self::assertTrue($this->firstComment()->likedByUsers->contains($this->secondUser()));
    }

    public function test_user_can_like_comment()
    {
        $result = $this->firstUser()->likeComment($this->firstComment());

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedComment($this->firstUser(), $this->firstComment()));

        // check liking same post do not throw sql exception
        $result = $this->firstUser()->likeComment($this->firstComment());

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedComment($this->firstUser(), $this->firstComment()));

        $result = $this->firstUser()->likeComment($this->secondComment());

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedComment($this->firstUser(), $this->secondComment()));
    }

    public function test_comment_can_like_as_user()
    {
        $result = $this->firstComment()->likeAs($this->firstUser());

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedComment($this->firstUser(), $this->firstComment()));

        // check liking same post do not throw sql exception
        $result = $this->firstComment()->likeAs($this->firstUser());

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedComment($this->firstUser(), $this->firstComment()));

        $result = $this->secondComment()->likeAs($this->firstUser());

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedComment($this->firstUser(), $this->secondComment()));
    }

    public function test_user_can_unlike_comment()
    {
        $this->likeCommentAsUser($this->firstUser(), $this->firstComment());

        $result = $this->firstUser()->unlikeComment($this->firstComment());

        self::assertTrue($result);
        self::assertEquals(0, $this->userHasLikedComment($this->firstUser(), $this->firstComment()));

        $result = $this->firstUser()->unlikeComment($this->secondComment());

        self::assertFalse($result);
        self::assertEquals(0, $this->userHasLikedComment($this->firstUser(), $this->secondComment()));
    }

    public function test_comment_can_unlike_as_user()
    {
        $this->likeCommentAsUser($this->firstUser(), $this->firstComment());

        $result = $this->firstComment()->unlikeAs($this->firstUser());

        self::assertTrue($result);
        self::assertEquals(0, $this->userHasLikedComment($this->firstUser(), $this->firstComment()));

        $result = $this->secondComment()->unlikeAs($this->firstUser());

        self::assertFalse($result);
        self::assertEquals(0, $this->userHasLikedComment($this->firstUser(), $this->secondComment()));
    }

    public function test_user_has_liked_post_return_correct_result()
    {
        $result = $this->firstUser()->hasLikedComment($this->firstComment());

        self::assertFalse($result);

        $this->likeCommentAsUser($this->firstUser(), $this->firstComment());

        $result = $this->firstUser()->hasLikedComment($this->firstComment());

        self::assertTrue($result);
    }

    public function test_post_is_liked_by_return_correct_result()
    {
        $result = $this->firstComment()->isLikedBy($this->firstUser());

        self::assertFalse($result);

        $this->likeCommentAsUser($this->firstUser(), $this->firstComment());

        $result = $this->firstComment()->isLikedBy($this->firstUser());

        self::assertTrue($result);
    }
}
