<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts_tags', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('post_id')->unsigned()->index();
			$table->integer('tag_id')->unsigned()->index();
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('CASCADE')->onUpdate('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts_tags');
	}

}
