<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use App\Models\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoryTest extends TestCase
{
    use RefreshDatabase;

    public function seedTestData()
    {
        User::factory(5)
            ->has(Post::factory())
            ->has(Story::factory())
            ->create();
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

    public function test_user_return_correct_relation()
    {
        $this->seedTestData();

        $story = Story::find(1);

        self::assertInstanceOf(User::class, $story->user);
    }


    public function test_views_return_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $story = Story::find(1);

        self::assertInstanceOf(Collection::class, $story->views);

        $view = $story->views()->create(['user_id' => $user->id]);

        $story->refresh();

        self::assertTrue($story->views->contains($view));
        self::assertCount(1, $story->views);

        $views = $story->views()->createMany([
            ['user_id' => 2],
            ['user_id' => 3],
            ['user_id' => 4],
            ['user_id' => 5],
        ]);

        $story->refresh();

        $views->each(function ($view) use ($story) {
            self::assertTrue($story->views->contains($view));
        });

        self::assertCount(5, $story->views);
    }

    public function test_view_as_create_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $story = Story::find(1);

        self::assertCount(0, $this->findView($user, $story));

        $story->viewAs($user);

        self::assertCount(1, $this->findView($user, $story));

        $story->viewAs($user);

        self::assertCount(1, $this->findView($user, $story));
    }

    public function test_remove_view_as_delete_correct_relation()
    {
        $this->seedTestData();

        $user = User::find(1);

        $story = Story::find(1);

        $this->viewStory($user, $story);

        $result = $story->removeViewAs($user);

        self::assertTrue($result);
        self::assertCount(0, $this->findView($user, $story));

        $result = $story->removeViewAs($user);

        self::assertFalse($result);
        self::assertCount(0, $this->findView($user, $story));
    }

    public function test_viewed_by_users_return_correct_relation()
    {
        $this->seedTestData();

        $users = Collection::make([1, 2, 3])->map(function ($i) {
            return User::find($i);
        });

        $story = Story::find(1);

        self::assertInstanceOf(Collection::class, $story->viewedByUsers);

        $story->viewedByUsers()->attach($users[0], ['viewable_type' => Story::class]);

        $story->refresh();

        self::assertTrue($story->viewedByUsers->contains($users[0]));
        self::assertCount(1, $story->viewedByUsers);

        $story->viewedByUsers()->attach($users[1], ['viewable_type' => Story::class]);
        $story->viewedByUsers()->attach($users[2], ['viewable_type' => Story::class]);

        $story->refresh();

        self::assertTrue($story->viewedByUsers->contains($users[1]));
        self::assertTrue($story->viewedByUsers->contains($users[2]));
        self::assertCount(3, $story->viewedByUsers);
    }

    public function test_is_viewed_by_return_correct_result()
    {
        $this->seedTestData();

        $user = User::find(1);

        $story = Story::find(1);

        self::assertFalse($story->isViewedBy($user));

        $this->viewStory($user, $story);

        self::assertTrue($story->isViewedBy($user));
    }
}
