<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id')->on('posts')->references('id')
            ->onDelete('CASCADE')
            ->onUpdate('CASCADE');
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->on('tags')->references('id')
            ->onDelete('CASCADE')
            ->onUpdate('CASCADE');

            $table->index(['post_id','tag_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_tag');
    }
};
