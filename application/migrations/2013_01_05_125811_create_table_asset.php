<?php

class Create_Table_Asset {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
        //asset
        Schema::create('asset', function($table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('code', 10)->nullable();
            $table->integer('type_id')->unsigned();
            $table->index('type_id');
            $table->integer('configured_by')->unsigned();
            $table->index('configured_by');
            $table->boolean('status')->default(true);
            $table->string('condition', 1)->default('G');
            $table->integer('quantity')->default(0);
            $table->string('description', 255)->nullable();
            $table->string('comments', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('vendor', 120)->nullable();
            $table->decimal('purchase_price', 14, 2)->default(0.00);
            $table->timestamps();
            $table->foreign('type_id')
                ->references('id')
                ->on('asset_type')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('configured_by')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
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
            'asset',
        ));
	}

}