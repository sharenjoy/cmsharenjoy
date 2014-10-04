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
		if ( !Schema::hasTable('members') )
        {
            Schema::create('members', function($table)
            {

                $table->engine = 'InnoDB';
                $table->increments('id');

                $table->string('email',255)->unique();
                $table->string('password',255);
                $table->string('name',255);
                $table->string('phone',255)->nullable();;
                $table->string('mobile',255)->nullable();;
                $table->string('first_name',255)->nullable();
                $table->string('last_name',255)->nullable();
                $table->text('description')->nullable();
                $table->boolean('activated')->default(0);
				$table->string('activation_code')->nullable();
				$table->timestamp('activated_at')->nullable();
                $table->dateTime('last_login')->nullable();
                $table->string('remember_token',100)->nullable();
                $table->timestamps();

            });
        }
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
