<?php

namespace Tests\Feature\Models;

use App\Models\Blocking;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Save;
use App\Models\Story;
use App\Models\User;
use App\Models\View;
use App\Notifications\CommentNotification;
use App\Notifications\FollowNotification;
use App\Notifications\LikeNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function seedTestData()
    {
        User::factory(5)
            ->hasStories(1)
            ->hasPosts(1)
            ->create();

        User::all()->each(function ($user) {
            Post::all()->each(function ($post) use ($user) {
                Comment::factory()->for($user)->for($post)->create();
            });
        });
    }

    public function follow(User $follower, User $following)
    {
        return Following::create([
            'follower_id' => $follower->id,
            'following_id' => $following->id
        ]);
    }

    public function likePost($user, $post)
    {
        return Like::create([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class
        ]);
    }

    public function likeComment($user, $comment)
    {
        return Like::create([
            'user_id' => $user->id,
            'likeable_id' => $comment->id,
            'likeable_type' => Comment::class
        ]);
    }

    public function savePost($user, $post)
    {
        return Save::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }

    public function findSave($user, $post)
    {
        return Save::whereUserId($user->id)
            ->wherePostId($post->id)
            ->get();
    }

    public function viewStory($user, $story)
    {
        return View::create([
            'user_id' => $user->id,
            'viewable_id' => $story->id,
            'viewable_type' => Story::class
        ]);
    }

    public function findView($user, $story)
    {
        return View::whereUserId($user->id)
            ->whereViewableId($story->id)
            ->whereViewableType(Story::class)
            ->get();
    }

    public function block($blocker, $blocking)
    {
        return Blocking::create([
            'blocker_id' => $blocker->id,
            'blocking_id' => $blocking->id
        ]);
    }

    public function test_create_validation()
    {
        $this->seedTestData();

        self::assertTrue(true);
    }

    public function test_followings_return_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);
        $third = User::find(3);

        self::assertInstanceOf(Collection::class, $first->followings);

        $first->followings()->attach($second);

        $first = User::find(1);

        self::assertTrue($first->followings->contains($second));
        self::assertEquals(1, $first->followings->count());

        $first->followings()->attach($third);

        $first = User::find(1);

        self::assertTrue($first->followings->contains($third));
        self::assertEquals(2, $first->followings->count());

        $first->followings()->attach($first);

        $first = User::find(1);

        self::assertTrue($first->followings->contains($first));
        self::assertEquals(3, $first->followings->count());
    }

    public function test_followers_return_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);
        $third = User::find(3);

        self::assertInstanceOf(Collection::class, $first->followers);

        $first->followers()->attach($second);

        $first = User::find(1);

        self::assertTrue($first->followers->contains($second));
        self::assertEquals(1, $first->followers->count());

        $first->followers()->attach($third);

        $first = User::find(1);

        self::assertTrue($first->followers->contains($third));
        self::assertEquals(2, $first->followers->count());

        $first->followers()->attach($first);

        $first = User::find(1);

        self::assertTrue($first->followers->contains($first));
        self::assertEquals(3, $first->followers->count());
    }

    public function test_is_following_return_correct_result()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        self::assertFalse($first->isFollowing($second));

        $this->follow($first, $second);

        self::assertTrue($first->isFollowing($second));

        self::assertFalse($second->isFollowing($first));

        $this->follow($second, $first);

        self::assertTrue($second->isFollowing($first));
    }

    public function test_is_followed_by_return_correct_result()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        self::assertFalse($first->isFollowedBy($second));

        $this->follow($second, $first);

        self::assertTrue($first->isFollowedBy($second));

        self::assertFalse($second->isFollowedBy($first));

        $this->follow($first, $second);

        self::assertTrue($second->isFollowedBy($first));
    }

    public function test_are_following_each_other_return_correct_result()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        self::assertFalse($first->areFollowingEachOther($second));
        self::assertFalse($second->areFollowingEachOther($first));

        $this->follow($first, $second);

        self::assertFalse($first->areFollowingEachOther($second));
        self::assertFalse($second->areFollowingEachOther($first));

        $this->follow($second, $first);

        self::assertTrue($first->areFollowingEachOther($second));
        self::assertTrue($second->areFollowingEachOther($first));
    }

    public function test_follow_create_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        $f = $first->follow($second);

        self::assertCount(1, $second->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->id', $f->id)->get());
        self::assertTrue($f->is(Following::find($f->id)));

        $f = $first->follow($second);

        self::assertCount(1, $second->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->id', $f->id)->get());
        self::assertEquals($f, Following::find($f->id));

        $this->expectException(ValidationException::class);

        $f = $first->follow($first);

        self::assertCount(0, $first->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->id', $f->id)->get());
        self::assertNull(Following::find($f->id));
    }

    public function test_unfollow_delete_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        $f = $first->unfollow($second);

        self::assertNull($f);
        self::assertCount(0, $second->notifications()
            ->where('type', FollowNotification::class)
            ->get());
        self::assertEquals(0, Following::whereFollowerId($first->id)
            ->whereFollowingId($second->id)->count());

        $f = $this->follow($first, $second);

        self::assertCount(1, $second->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->id', $f->id)->get());
        self::assertTrue($f->is(Following::find($f->id)));

        $f = $first->unfollow($second);

        self::assertInstanceOf(Following::class, $f);
        self::assertCount(0, $second->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->id', $f->id)->get());
        self::assertNull(Following::find($f->id));

        $this->expectException(ValidationException::class);

        $f = $this->follow($first, $first);

        self::assertCount(0, $first->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->id', $f->id)->get());
        self::assertNull(Following::find($f->id));

        $f = $first->unfollow($first);

        self::assertNull($f);
        self::assertCount(0, $second->notifications()
            ->where('type', FollowNotification::class)
            ->get());
        self::assertEquals(0, Following::whereFollowerId($first->id)
            ->whereFollowingId($second->id)->count());
    }

    // TODO need to test unfollowEachOther

    //    public function test_user_can_toggle_follow_user()
    //    {
    //        $this->seedTestData();
    //
    //        $first = User::find(1);
    //        $second = User::find(2);
    //
    //        $result = $first->toggleFollow($second);
    //
    //        self::assertEquals([$second->id], $result['attached']);
    //        self::assertEquals(1, Following::whereFollowerId($first->id)
    //            ->whereFollowingId($second->id)->count());
    //
    //        $result = $first->toggleFollow($second);
    //
    //        self::assertEquals([$second->id], $result['detached']);
    //        self::assertEquals(0, Following::whereFollowerId($first->id)
    //            ->whereFollowingId($second->id)->count());
    //    }

    public function test_likes_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstPost = Post::find(1);

        $firstComment = Comment::find(1);

        self::assertInstanceOf(Collection::class, $user->likes);

        $like = $user->likes()->create([
            'likeable_id' => $firstPost->id,
            'likeable_type' => Post::class
        ]);

        $user = User::find(1);

        self::assertTrue($user->likes->contains($like));
        self::assertCount(1, $user->likes);

        $like = $user->likes()->create([
            'likeable_id' => $firstComment->id,
            'likeable_type' => Comment::class
        ]);

        $user = User::find(1);

        self::assertTrue($user->likes->contains($like));
        self::assertCount(2, $user->likes);
    }

    public function test_liked_posts_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstPost = Post::find(1);
        $secondPost = Post::find(2);

        self::assertInstanceOf(Collection::class, $user->likedPosts);

        $user->likedPosts()->attach($firstPost, ['likeable_type' => Post::class]);

        $user = User::find(1);
        self::assertTrue($user->likedPosts->contains($firstPost));
        self::assertCount(1, $user->likedPosts);

        $user->likedPosts()->attach($secondPost, ['likeable_type' => Post::class]);

        $user = User::find(1);
        self::assertTrue($user->likedPosts->contains($secondPost));
        self::assertCount(2, $user->likedPosts);
    }

    public function test_has_liked_post_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstPost = Post::find(1);

        self::assertFalse($user->hasLikedPost($firstPost));

        $this->likePost($user, $firstPost);

        self::assertTrue($user->hasLikedPost($firstPost));
    }

    public function test_like_post_create_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstPost = Post::find(1);
        $secondPost = Post::find(2);

        $like = $user->likePost($firstPost);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $user->likePost($firstPost);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());


        $like = $user->likePost($secondPost);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());
    }

    function test_unlike_post_delete_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstPost = Post::find(1);
        $secondPost = Post::find(2);

        $like1 = $this->likePost($user, $firstPost);
        $like2 = $this->likePost($user, $secondPost);

        self::assertTrue($like1->is(Like::find($like1->id)));
        self::assertCount(1, $like1->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like1->id)
            ->get());
        self::assertTrue($like2->is(Like::find($like2->id)));
        self::assertCount(1, $like2->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like2->id)
            ->get());

        $like = $user->unlikePost($firstPost);

        self::assertInstanceOf(Like::class, $like);
        self::assertNull(Like::find($like->id));
        self::assertCount(0, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $user->unlikePost($firstPost);

        self::assertNull($like);

        $like = $user->unlikePost($secondPost);

        self::assertInstanceOf(Like::class, $like);
        self::assertNull(Like::find($like->id));
        self::assertCount(0, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());
    }

    public function test_liked_comments_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstComment = Comment::find(1);
        $secondComment = COmment::find(2);

        self::assertInstanceOf(Collection::class, $user->likedComments);

        $user->likedComments()->attach($firstComment, ['likeable_type' => Comment::class]);

        $user = User::find(1);
        self::assertTrue($user->likedComments->contains($firstComment));
        self::assertCount(1, $user->likedComments);

        $user->likedComments()->attach($secondComment, ['likeable_type' => Comment::class]);

        $user = User::find(1);
        self::assertTrue($user->likedComments->contains($secondComment));
        self::assertCount(2, $user->likedComments);
    }

    public function test_has_liked_comment_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstComment = Comment::find(1);

        self::assertFalse($user->hasLikedComment($firstComment));

        $this->likeComment($user, $firstComment);

        self::assertTrue($user->hasLikedComment($firstComment));
    }

    public function test_like_comment_create_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstComment = Comment::find(1);
        $secondComment = Comment::find(2);

        $like = $user->likeComment($firstComment);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $user->likeComment($firstComment);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());


        $like = $user->likeComment($secondComment);

        self::assertTrue($like->is(Like::find($like->id)));
        self::assertCount(1, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());
    }

    function test_unlike_comment_delete_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $firstComment = Comment::find(1);
        $secondComment = Comment::find(2);

        $like1 = $this->likeComment($user, $firstComment);
        $like2 = $this->likeComment($user, $secondComment);

        self::assertTrue($like1->is(Like::find($like1->id)));
        self::assertCount(1, $like1->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like1->id)
            ->get());
        self::assertTrue($like2->is(Like::find($like2->id)));
        self::assertCount(1, $like2->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like2->id)
            ->get());

        $like = $user->unlikeComment($firstComment);

        self::assertInstanceOf(Like::class, $like);
        self::assertNull(Like::find($like->id));
        self::assertCount(0, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());

        $like = $user->unlikeComment($firstComment);

        self::assertNull($like);

        $like = $user->unlikeComment($secondComment);

        self::assertInstanceOf(Like::class, $like);
        self::assertNull(Like::find($like->id));
        self::assertCount(0, $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->get());
    }

    public function test_posts_return_correct_relation()
    {
        User::factory()->create();

        $user = User::find(1);

        self::assertInstanceOf(Collection::class, $user->posts);

        $post = $user->posts()->create(['caption' => 'Test']);

        $user = User::find(1);

        self::assertTrue($user->posts->contains($post));
        self::assertCount(1, $user->posts);

        $posts = $user->posts()->createMany([
            ['caption' => 'Test'],
            ['caption' => 'Test']
        ]);

        $user = User::find(1);

        self::assertTrue($user->posts->contains($posts[0]));
        self::assertTrue($user->posts->contains($posts[1]));
        self::assertCount(3, $user->posts);
    }

    public function test_post_images_return_correct_relation()
    {
        User::factory()
            ->has(Post::factory(3)->has(PostImage::factory(3), 'images'))
            ->create();

        $user = User::find(1);

        self::assertInstanceOf(Collection::class, $user->postImages);
        self::assertCount(9, $user->postImages);
    }

    public function test_latest_post_images_return_correct_relation()
    {
        User::factory()
            ->has(Post::factory(3)->has(PostImage::factory(3), 'images'))
            ->create();

        $user = User::find(1);

        self::assertInstanceOf(Collection::class, $user->latestPostImages);
        self::assertCount(3, $user->latestPostImages);
    }

    public function test_comments_return_correct_relation()
    {
        User::factory()->has(Post::factory())->create();

        $user = User::find(1);
        $post = $user->posts()->first();

        self::assertInstanceOf(Collection::class, $user->comments);

        $comment = $user->comments()->create([
            'post_id' => $post->id,
            'body' => 'Test'
        ]);

        $user = User::find(1);

        self::assertTrue($user->comments->contains($comment));
        self::assertCount(1, $user->comments);

        $comments = $user->comments()->createMany([
            ['post_id' => $post->id, 'body' => 'Test'],
            ['post_id' => $post->id, 'body' => 'Test'],
        ]);

        $user = User::find(1);

        self::assertTrue($user->comments->contains($comments[0]));
        self::assertTrue($user->comments->contains($comments[1]));
        self::assertCount(3, $user->comments);
    }

    public function test_comment_post_create_correct_relation()
    {
        User::factory(2)->has(Post::factory())->create();

        $user = User::find(1);
        $post = $user->posts()->first();
        $otherPost = Post::whereNotIn('user_id', [$user->id])->first();

        $parentComment = $user->commentPost($post, 'Test');

        self::assertTrue($parentComment->is(Comment::find($parentComment->id)));
        self::assertCount(0, $parentComment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $parentComment->id)
            ->get());

        $comment = $user->commentPost($post, 'Test', $parentComment);

        self::assertTrue($comment->is(Comment::find($comment->id)));
        self::assertCount(0, $parentComment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $parentComment->id)
            ->get());

        $parentComment = $user->commentPost($otherPost, 'Test');

        self::assertTrue($parentComment->is(Comment::find($parentComment->id)));
        self::assertCount(1, $parentComment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $parentComment->id)
            ->get());

        $comment = $user->commentPost($otherPost, 'Test', $parentComment);

        self::assertTrue($comment->is(Comment::find($comment->id)));
        self::assertCount(1, $parentComment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $parentComment->id)
            ->get());
    }

    public function test_saves_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $posts = [Post::find(1), Post::find(2), Post::find(3)];

        self::assertInstanceOf(Collection::class, $user->saves);

        $save = $user->saves()->create(['post_id' => $posts[0]->id]);

        $user = User::find(1);

        self::assertTrue($user->saves->contains($save));
        self::assertCount(1, $user->saves);

        $saves = $user->saves()->createMany([
            ['post_id' => $posts[1]->id],
            ['post_id' => $posts[2]->id]
        ]);

        $user = User::find(1);

        self::assertTrue($user->saves->contains($saves[0]));
        self::assertTrue($user->saves->contains($saves[1]));
        self::assertCount(3, $user->saves);
    }

    public function test_saved_posts_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $posts = collect([1, 2])->map(function ($i) {
            return Post::find($i);
        });

        self::assertInstanceOf(Collection::class, $user->savedPosts);

        $user->savedPosts()->attach($posts[0]);

        $user = User::find(1);

        self::assertTrue($user->savedPosts->contains($posts[0]));
        self::assertCount(1, $user->savedPosts);

        $user->savedPosts()->attach($posts[1]);

        $user = User::find(1);

        self::assertTrue($user->savedPosts->contains($posts[1]));
        self::assertCount(2, $user->savedPosts);
    }

    public function test_has_saved_post_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $post = Post::find(1);

        self::assertFalse($user->hasSavedPost($post));

        $this->savePost($user, $post);

        self::assertTrue($user->hasSavedPost($post));
    }

    public function test_save_post_create_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $posts = collect([1, 2])->map(function ($i) {
            return Post::find($i);
        });

        $result = $user->savePost($posts[0]);

        self::assertTrue($result);
        self::assertCount(1, $this->findSave($user, $posts[0]));

        $result = $user->savePost($posts[0]);

        self::assertTrue($result);
        self::assertCount(1, $this->findSave($user, $posts[0]));

        $result = $user->savePost($posts[1]);

        self::assertTrue($result);
        self::assertCount(1, $this->findSave($user, $posts[1]));
    }

    public function test_unsave_post_delete_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $posts = collect([1, 2])->map(function ($i) {
            return Post::find($i);
        });

        $this->savePost($user, $posts[0]);
        $this->savePost($user, $posts[1]);

        $result = $user->unsavePost($posts[0]);

        self::assertTrue($result);
        self::assertCount(0, $this->findSave($user, $posts[0]));


        $result = $user->unsavePost($posts[0]);

        self::assertFalse($result);
        self::assertCount(0, $this->findSave($user, $posts[0]));

        $result = $user->unsavePost($posts[1]);

        self::assertTrue($result);
        self::assertCount(0, $this->findSave($user, $posts[1]));
    }

    public function test_stories_return_correct_relation()
    {
        User::factory()->createOne();

        $user = User::find(1);

        Story::factory(5)->for($user)->create();

        self::assertInstanceOf(Collection::class, $user->stories);
        self::assertCount(5, $user->stories);
    }

    public function test_views_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $stories = collect([1, 2, 3])->map(function ($i) {
            return Story::find($i);
        });

        $view = $user->views()->create([
            'viewable_id' => $stories[0]->id,
            'viewable_type' => Story::class
        ]);

        $user = User::find(1);

        self::assertTrue($user->views->contains($view));
        self::assertCount(1, $user->views);

        $views = $user->views()->createMany([
            ['viewable_id' => $stories[1]->id, 'viewable_type' => Story::class],
            ['viewable_id' => $stories[2]->id, 'viewable_type' => Story::class]
        ]);

        $user = User::find(1);

        self::assertTrue($user->views->contains($views[0]));
        self::assertTrue($user->views->contains($views[1]));
        self::assertCount(3, $user->views);
    }

    public function test_viewed_stories_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $stories = collect([1, 2])->map(function ($i) {
            return Story::find($i);
        });

        self::assertInstanceOf(Collection::class, $user->viewedStories);

        $user->viewedStories()->attach($stories[0], ['viewable_type' => Story::class]);

        $user = User::find(1);

        self::assertTrue($user->viewedStories->contains($stories[0]));
        self::assertCount(1, $user->viewedStories);

        $user->viewedStories()->attach($stories[1], ['viewable_type' => Story::class]);

        $user = User::find(1);

        self::assertTrue($user->viewedStories->contains($stories[1]));
        self::assertCount(2, $user->viewedStories);
    }

    public function test_has_viewed_story_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $story = Story::find(1);

        self::assertFalse($user->hasViewedStory($story));

        $this->viewStory($user, $story);

        self::assertTrue($user->hasViewedStory($story));
    }

    public function test_has_viewed_stories_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $stories = Collection::make([1, 2, 3])->map(function ($i) {
            return Story::find($i);
        });

        self::assertFalse($user->hasViewedStories($stories));

        $this->viewStory($user, $stories[0]);

        self::assertFalse($user->hasViewedStories($stories));

        $this->viewStory($user, $stories[1]);

        self::assertFalse($user->hasViewedStories($stories));

        $this->viewStory($user, $stories[2]);

        self::assertTrue($user->hasViewedStories($stories));
    }

    public function test_view_story_create_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $story = Story::find(1);

        self::assertCount(0, $this->findView($user, $story));

        $view = $user->viewStory($story);

        self::assertTrue($this->findView($user, $story)->contains($view));
        self::assertCount(1, $this->findView($user, $story));

        $view = $user->viewStory($story);

        self::assertTrue($this->findView($user, $story)->contains($view));
        self::assertCount(1, $this->findView($user, $story));
    }

    public function test_remove_view_story_delete_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $story = Story::find(1);

        $this->viewStory($user, $story);

        self::assertCount(1, $this->findView($user, $story));

        $result = $user->removeViewStory($story);

        self::assertTrue($result);
        self::assertCount(0, $this->findView($user, $story));

        $result = $user->removeViewStory($story);

        self::assertFalse($result);
        self::assertCount(0, $this->findView($user, $story));
    }

    public function test_blockings_return_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);
        $third = User::find(3);

        self::assertInstanceOf(Collection::class, $first->blockings);

        $first->blockings()->attach($second);

        $first = User::find(1);

        self::assertTrue($first->blockings->contains($second));
        self::assertEquals(1, $first->blockings->count());

        $first->blockings()->attach($third);

        $first = User::find(1);

        self::assertTrue($first->blockings->contains($third));
        self::assertEquals(2, $first->blockings->count());

        $first->blockings()->attach($first);

        $first = User::find(1);

        self::assertTrue(!$first->blockings->contains($first));
        self::assertEquals(2, $first->blockings->count());
    }

    public function test_blockers_return_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);
        $third = User::find(3);

        self::assertInstanceOf(Collection::class, $first->blockers);

        $first->blockers()->attach($second);

        $first = User::find(1);

        self::assertTrue($first->blockers->contains($second));
        self::assertEquals(1, $first->blockers->count());

        $first->blockers()->attach($third);

        $first = User::find(1);

        self::assertTrue($first->blockers->contains($third));
        self::assertEquals(2, $first->blockers->count());

        $first->blockers()->attach($first);

        $first = User::find(1);

        self::assertTrue(!$first->blockers->contains($first));
        self::assertEquals(2, $first->blockers->count());
    }

    public function test_is_blocking_return_correct_result()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        self::assertFalse($first->isBlocking($second));

        $this->block($first, $second);

        self::assertTrue($first->isBlocking($second));

        self::assertFalse($second->isBlocking($first));

        $this->block($second, $first);

        self::assertTrue($second->isBlocking($first));
    }

    public function test_is_blocked_by_return_correct_result()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        self::assertFalse($first->isBlockedBy($second));

        $this->block($second, $first);

        self::assertTrue($first->isBlockedBy($second));

        self::assertFalse($second->isBlockedBy($first));

        $this->block($first, $second);

        self::assertTrue($second->isBlockedBy($first));
    }

    public function test_block_create_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        $f1 = $this->follow($first, $second);
        $f2 = $this->follow($second, $first);

        $b = $first->block($second);

        self::assertTrue($b->is(Blocking::find($b->id)));
        self::assertNull(Following::find($f1->id));
        self::assertNull(Following::find($f2->id));

        $b = $first->block($second);

        self::assertTrue($b->is(Blocking::find($b->id)));

        $this->expectException(ValidationException::class);

        $b = $first->block($first);

        self::assertNull($b);
    }

    public function test_unblock_delete_correct_relation()
    {
        $this->seedTestData();

        $first = User::find(1);
        $second = User::find(2);

        $b = $first->unblock($second);

        self::assertNull($b);
        self::assertEquals(0, Blocking::whereBlockerId($first->id)
            ->whereBlockingId($second->id)->count());

        $b = $this->block($first, $second);

        self::assertTrue($b->is(Blocking::find($b->id)));

        $b = $first->unblock($second);

        self::assertInstanceOf(Blocking::class, $b);
        self::assertNull(Blocking::find($b->id));

        $this->expectException(ValidationException::class);

        $b = $this->block($first, $first);

        self::assertNull(Blocking::find($b->id));

        $b = $first->unblock($first);

        self::assertNull($b);
        self::assertEquals(0, Blocking::whereBlockerId($first->id)
            ->whereBlockingId($second->id)->count());
    }
}
