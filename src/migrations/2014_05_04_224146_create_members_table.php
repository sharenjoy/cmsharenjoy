<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('members', function($table)
		{
			$table->engine = 'InnoDB';

		    $table->increments('id')->index();
		    $table->integer('user_id')->unsigned()->index()->nullable()->default(0);
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name', 255);
            $table->string('mobile', 255);
            $table->string('tel', 255);
            $table->string('address', 255);
            $table->text('description')->nullable();
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
		Schema::drop('members');
	}

}
