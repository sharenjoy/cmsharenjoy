<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable()->default(0);
			$table->string('tag');
			$table->string('slug')->unique();
            $table->integer('sort')->unsigned()->nullable()->default(0);
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
		Schema::drop('tags');
	}

}
