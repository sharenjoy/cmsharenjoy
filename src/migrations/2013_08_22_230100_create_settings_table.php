<?php

use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('settings') )
        {
            Schema::create('settings', function($table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id')->index();
                $table->string('key', 255)->unique();
                $table->string('label', 255);
                $table->text('description')->nullable();
                $table->string('type', 255);
                $table->string('value', 255);
                $table->string('module', 255);
                $table->integer('sort')->unsigned()->nullable()->default(0);
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
        Schema::drop('settings');
    }

}