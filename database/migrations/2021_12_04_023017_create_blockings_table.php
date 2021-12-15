<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blockings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blocking_id');
            $table->unsignedBigInteger('blocker_id');
            $table->timestamps();
            $table->foreign('blocking_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('blocker_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->unique(['blocking_id', 'blocker_id']);

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
        Schema::dropIfExists('blockings');
    }
}
