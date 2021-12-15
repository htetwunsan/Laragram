<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('following', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('following_id');
            $table->unsignedBigInteger('follower_id');
            $table->timestamps();
            $table->foreign('following_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('follower_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->unique(['following_id', 'follower_id']);

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('following');
    }
}
