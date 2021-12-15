<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('name');
            $table->date('birthday');
            $table->string('password');
            $table->string('profile_image', 2047)->nullable();
            $table->string('website', 2047)->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->enum('gender', ['prefer not to say', 'male', 'female'])->default('prefer not to say');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
