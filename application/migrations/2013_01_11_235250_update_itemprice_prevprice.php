<?php

class Update_Itemprice_Prevprice {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('item_price', function($table) {
            $table->decimal('prev_price', 14, 2)->default(0);
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_price', function($table) {
            $table->drop_column(array('prev_price'));
        });
    }
}