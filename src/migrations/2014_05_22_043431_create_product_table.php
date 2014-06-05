<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->default(1);
            $table->integer('category_id')->index()->unsigned()->default(0);
		    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title', 255);
            $table->string('title_jp', 255);
            $table->text('description');
            $table->integer('price')->unsigned()->default(0);
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
		Schema::drop('products');
	}

}
