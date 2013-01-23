<?php

class Create_Synchronize {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('synchronize', function($table) {
            $table->increments('id');
            $table->integer('systrace')->default(1);
            $table->string('table', 50)->unique();
            $table->timestamp('sync_time');
            $table->timestamp('last_update');
            $table->integer('last_id');

        });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('synchronize');
	}

}