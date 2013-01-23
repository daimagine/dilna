<?php

class Create_Table_Asset_Type {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
        //asset_type
        Schema::create('asset_type', function($table) {
            $table->increments('id');
            $table->string('name', 80);
            $table->string('description', 255);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop(array(
            'asset_type',
        ));
	}

}