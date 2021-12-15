<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikePostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory(2)->has(Post::factory())->create();
    }

    public function likePostAsUser($user, $post)
    {
        return Like::create([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class
        ]);
    }

    public function userHasLikedPost($user, $post)
    {
        return Like::whereUserId($user->id)
            ->whereLikeableId($post->id)->whereLikeableType(Post::class)->count();
    }

    public function test_user_likes_return_correct_relation()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);
        $secondUserPost = Post::whereUserId($secondUser->id)->first();

        self::assertInstanceOf(Collection::class, $firstUser->likes);

        // First user like first user's post
        $like = $this->likePostAsUser($firstUser, $firstUserPost);
        $firstUser = User::find(1);
        self::assertEquals(1, $firstUser->likes->count());
        self::assertTrue($firstUser->likes->contains($like));

        // First user like second user's post
        $like = $this->likePostAsUser($firstUser, $secondUserPost);
        $firstUser = User::find(1);
        self::assertEquals(2, $firstUser->likes->count());
        self::assertTrue($firstUser->likes->contains($like));
    }

    public function test_user_liked_posts_return_correct_relation()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);
        $secondUserPost = Post::whereUserId($secondUser->id)->first();

        self::assertInstanceOf(Collection::class, $firstUser->likedPosts);

        // First user like first user's post
        $this->likePostAsUser($firstUser, $firstUserPost);
        $firstUser = User::find(1);
        self::assertEquals(1, $firstUser->likedPosts->count());
        self::assertTrue($firstUser->likedPosts->contains($firstUserPost));

        // First user like second user's post
        $this->likePostAsUser($firstUser, $secondUserPost);
        $firstUser = User::find(1);
        self::assertEquals(2, $firstUser->likedPosts->count());
        self::assertTrue($firstUser->likedPosts->contains($secondUserPost));
    }

    public function test_post_likes_return_correct_relation()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);

        self::assertInstanceOf(Collection::class, $firstUserPost->likes);

        // First user like first user's post
        $like = $this->likePostAsUser($firstUser, $firstUserPost);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        self::assertEquals(1, $firstUserPost->likes->count());
        self::assertTrue($firstUserPost->likes->contains($like));

        // Second user like first user's post
        $like = $this->likePostAsUser($secondUser, $firstUserPost);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        self::assertEquals(2, $firstUserPost->likes->count());
        self::assertTrue($firstUserPost->likes->contains($like));
    }

    public function test_post_liked_by_users_return_correct_relation()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);

        self::assertInstanceOf(Collection::class, $firstUserPost->likedByUsers);

        // First user like first user's post
        $this->likePostAsUser($firstUser, $firstUserPost);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        self::assertEquals(1, $firstUserPost->likedByUsers->count());
        self::assertTrue($firstUserPost->likedByUsers->contains($firstUser));

        // Second user like first user's post
        $this->likePostAsUser($secondUser, $firstUserPost);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        self::assertEquals(2, $firstUserPost->likedByUsers->count());
        self::assertTrue($firstUserPost->likedByUsers->contains($secondUser));
    }

    public function test_user_can_like_post()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);
        $secondUserPost = Post::whereUserId($secondUser->id)->first();

        $result = $firstUser->likePost($firstUserPost);

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedPost($firstUser, $firstUserPost));

        // check liking same post do not throw sql exception
        $result = $firstUser->likePost($firstUserPost);

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedPost($firstUser, $firstUserPost));

        $result = $firstUser->likePost($secondUserPost);

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedPost($firstUser, $secondUserPost));
    }

    public function test_post_can_like_as_user()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);
        $secondUserPost = Post::whereUserId($secondUser->id)->first();

        $result = $firstUserPost->likeAs($firstUser);

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedPost($firstUser, $firstUserPost));

        // check liking same post do not throw sql exception
        $result = $firstUserPost->likeAs($firstUser);

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedPost($firstUser, $firstUserPost));

        $result = $secondUserPost->likeAs($firstUser);

        self::assertInstanceOf(Like::class, $result);
        self::assertEquals(1, $this->userHasLikedPost($firstUser, $secondUserPost));
    }

    public function test_user_can_unlike_post()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);
        $secondUserPost = Post::whereUserId($secondUser->id)->first();

        $this->likePostAsUser($firstUser, $firstUserPost);

        $result = $firstUser->unlikePost($firstUserPost);

        self::assertTrue($result);
        self::assertEquals(0, $this->userHasLikedPost($firstUser, $firstUserPost));

        $result = $firstUser->unlikePost($secondUserPost);

        self::assertFalse($result);
        self::assertEquals(0, $this->userHasLikedPost($firstUser, $secondUserPost));
    }

    public function test_post_can_unlike_as_user()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();
        $secondUser = User::find(2);
        $secondUserPost = Post::whereUserId($secondUser->id)->first();

        $this->likePostAsUser($firstUser, $firstUserPost);

        $result = $firstUserPost->unlikeAs($firstUser);

        self::assertTrue($result);
        self::assertEquals(0, $this->userHasLikedPost($firstUser, $firstUserPost));

        $result = $secondUserPost->unlikeAs($firstUser);

        self::assertFalse($result);
        self::assertEquals(0, $this->userHasLikedPost($firstUser, $secondUserPost));
    }

    public function test_user_has_liked_post_return_correct_result()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();

        $result = $firstUser->hasLikedPost($firstUserPost);

        self::assertFalse($result);

        $this->likePostAsUser($firstUser, $firstUserPost);

        $result = $firstUser->hasLikedPost($firstUserPost);

        self::assertTrue($result);
    }

    public function test_post_is_liked_by_return_correct_result()
    {
        $firstUser = User::find(1);
        $firstUserPost = Post::whereUserId($firstUser->id)->first();

        $result = $firstUserPost->isLikedBy($firstUser);

        self::assertFalse($result);

        $this->likePostAsUser($firstUser, $firstUserPost);

        $result = $firstUserPost->isLikedBy($firstUser);

        self::assertTrue($result);
    }
}
