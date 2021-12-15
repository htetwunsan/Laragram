<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Following;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Story;
use App\Models\User;
use App\Notifications\CommentNotification;
use App\Notifications\FollowNotification;
use App\Notifications\LikeNotification;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // create users;
        $hws = User::whereEmail('htetwunsan2000@gmail.com')->first();
        if (is_null($hws)) {
            $hws = User::factory(['email' => 'htetwunsan2000@gmail.com', 'name' => 'Htet Wun San'])->createOne();
        }
        User::factory(100)
            ->addImage()
            ->timestampsBetween('-3 months')
            ->create();

        $users = User::whereNotIn('id', [$hws->id])->get();

        // create posts for random users;
        for ($_ = 0; $_ < 1000; ++$_) {
            $user = $users->random();
            Post::factory()
                ->timestampsBetween($user->created_at)
                ->has(PostImage::factory(rand(1, 5)), 'images')
                ->for($user)
                ->create();
        }

        $posts = Post::all();

        for ($_ = 0; $_ < 100; ++$_) {
            $user = $users->random();
            Story::factory(rand(1, 5))
                ->for($user)
                ->create();
        }

        // random users comment random posts
        for ($_ = 0; $_ < 1000; ++$_) {
            $post = $posts->random();
            $parent = Comment::factory()
                ->for($post)
                ->for($users->random())
                ->timestampsBetween($post->created_at)
                ->create();

            $this->updateCommentTimestamps($parent);

            $children = Comment::factory(rand(1, 3))
                ->for($post)
                ->for($users->random())
                ->timestampsBetween($parent->created_at)
                ->create(['comment_id' => $parent->id]);

            foreach ($children as $child) {
                Comment::factory(rand(1, 3))
                    ->for($post)
                    ->for($users->random())
                    ->timestampsBetween($child->created_at)
                    ->create(['comment_id' => $child->id]);
            }
        }

        $comments = Comment::all();

        // random users likes random posts
        for ($_ = 0; $_ < 1000; ++$_) {
            $post = $posts->random();
            $like = $users->random()->likePost($post);
            $this->updateLikeTimestamps($like);
        }

        // random users likes random comments
        for ($_ = 0; $_ < 1000; ++$_) {
            $comment = $comments->random();
            $like = $users->random()->likeComment($comment);
            $this->updateLikeTimestamps($like);
        }

        // random users follow random users
        for ($_ = 0; $_ < 1000; ++$_) {
            $follower = $users->random();
            $following = $users->random();
            if ($follower->id !== $following->id) {
                $this->updateFollowTimestamps($follower->follow($following));
            }
        }
    }

    public function updateCommentTimestamps(Comment $comment)
    {
        $comment->post->user->notifications()
            ->where('type', CommentNotification::class)
            ->where('data->id', $comment->id)
            ->update([
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at
            ]);
    }

    public function updateFollowTimestamps(Following $following)
    {
        $time = $this->faker->dateTimeBetween($following->following->created_at);
        $following->update(['created_at' => $time, 'updated_at' => $time]);
        $following->following->notifications()
            ->where('type', FollowNotification::class)
            ->where('data->id', $following->id)
            ->update([
                'created_at' => $time,
                'updated_at' => $time
            ]);
    }

    public function updateLikeTimestamps(Like $like)
    {
        $time = $this->faker->dateTimeBetween($like->likeable->created_at);
        $like->update(['created_at' => $time, 'updated_at' => $time]);
        $like->likeable->user->notifications()
            ->where('type', LikeNotification::class)
            ->where('data->id', $like->id)
            ->update([
                'created_at' => $time,
                'updated_at' => $time
            ]);
    }
}
