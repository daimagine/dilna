<?php

class Initialize_Main_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
        //access
        Schema::create('access', function($table){
            $table->increments('id');
            $table->string('name', 80);
            $table->string('description', 255)->nullable();
            $table->string('action', 255)->nullable()->unique();
            $table->boolean('status')->default(true);
            $table->boolean('parent')->default(false);
            $table->boolean('visible')->default(false);
            $table->string('type', 1)->default('L'); // M : Main nav, S : sub nav, L : link
            $table->integer('parent_id')->nullable();
            $table->string('image',200)->nullable();
            $table->timestamps();
        });

        //account
        Schema::create('account', function($table){
            $table->increments('id');
            $table->string('name', 80);
            $table->string('description', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->string('type', 1)->nullable(); // M : Main nav, S : sub nav, L : link
            $table->string('category', 1)->default('A');
            $table->timestamps();
        });

        //system_properties
        Schema::create('system_properties', function($table) {
            $table->string('field_key', 80);
            $table->string('field_value', 255)->nullable();
        });

        //conversation
        Schema::create('conversation', function($table) {
            $table->increments('id');
            $table->string('subject');
            $table->timestamps();
        });

        //customer
        Schema::create('customer', function($table) {
            $table->increments('id');
            $table->string('name', 80);
            $table->string('address1', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('post_code', 6)->nullable();
            $table->string('phone1', 16)->nullable();
            $table->string('phone2', 16)->nullable();
            $table->text('additional_info')->nullable();
            $table->timestamp('last_visit')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        //discount
        Schema::create('discount', function($table){
            $table->increments('id');
            $table->string('code', 50);
            $table->string('description', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->float('value')->default(0);
            $table->decimal('registration_fee', 14, 2)->default(0);
            $table->integer('duration')->default(0);
            $table->string('duration_period', 1)->default('M');
            $table->timestamps();
        });

        //role
        Schema::create('role', function($table){
            $table->increments('id');
            $table->string('name', 50);
            $table->boolean('status')->default(true);
            $table->integer('parent_id')->default(0);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });


        //service
        Schema::create('service', function($table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('description', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        //item_category
        Schema::create('item_category', function($table){
            $table->increments('id');
            $table->string('name', 80);
            $table->string('description', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->string('picture', 100);
            $table->timestamps();
        });

        //unit_type
        Schema::create('unit_type', function($table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('goods_type', 1)->nullable();
            $table->integer('item_category_id')->unsigned();
            $table->index('item_category_id');
            $table->timestamps();
            $table->foreign('item_category_id')
                ->references('id')
                ->on('item_category')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //item_type
        Schema::create('item_type', function($table){
            $table->increments('id');
            $table->integer('item_category_id')->unsigned();
            $table->index('item_category_id');
            $table->string('name', 80);
            $table->string('description', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreign('item_category_id')
                ->references('id')
                ->on('item_category')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //item
        Schema::create('item', function($table){
            $table->increments('id');
            $table->integer('item_type_id')->unsigned();
            $table->index('item_type_id');
            $table->integer('item_category_id')->unsigned();
            $table->index('item_category_id');
            $table->integer('unit_id')->unsigned();
            $table->index('unit_id');
            $table->string('name', 150);
            $table->string('code', 10)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimum')->default(0);
            $table->string('description', 255)->nullable();
            $table->decimal('price', 14, 2)->default(0.00);
            $table->string('vendor', 120)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('expiry_date')->nullable();
            $table->decimal('purchase_price', 14, 2)->default(0.00);
            $table->timestamps();
            $table->foreign('item_category_id')
                ->references('id')
                ->on('item_category')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('item_type_id')
                ->references('id')
                ->on('item_type')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('unit_id')
                ->references('id')
                ->on('unit_type')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //user
        Schema::create('user', function($table) {
            $table->increments('id');
            $table->string('login_id', 30)->unique();
            $table->string('name', 80);
            $table->string('staff_id', 30);
            $table->string('password', 80);
            $table->string('address1', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('phone1', 16)->nullable();
            $table->string('phone2', 16)->nullable();
            $table->integer('role_id')->unsigned();
            $table->index('role_id');
            $table->boolean('status')->default(true);
            $table->string('picture', 100)->nullable();
            $table->timestamps();
            $table->foreign('role_id')
                ->references('id')
                ->on('role')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //item_price
        Schema::create('item_price', function($table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->index('item_id');
            $table->integer('configured_by')->unsigned();
            $table->index('configured_by');
            $table->decimal('price', 14, 2)->default(0.00);
            $table->decimal('prev_price', 14, 2)->default(0.00);
            $table->decimal('purchase_price', 14, 2)->default(0.00);
            $table->boolean('status')->default(true);
            $table->timestamp('date');
            $table->timestamp('expiry_date');
            $table->timestamps();
            $table->foreign('item_id')
                ->references('id')
                ->on('item')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('configured_by')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //batch
        Schema::create('batch', function($table) {
            $table->increments('id');
            $table->boolean('batch_status')->default(0);
            $table->timestamp('open_time')->nullable();
            $table->timestamp('close_time')->nullable();
            $table->integer('sales_count')->default(0.0);
            $table->decimal('sales_amount', 14, 2)->default(0.0);
            $table->decimal('clerk_amount', 14, 2)->default(0.0);
            $table->boolean('clerk_status')->default(0);
            $table->timestamp('clerk_time')->nullable();
            $table->integer('clerk_user_id')->nullable()->unsigned();
            $table->index('clerk_user_id');
            $table->decimal('fraction_50', 14, 2)->default(0.0);
            $table->decimal('fraction_100', 14, 2)->default(0.0);
            $table->decimal('fraction_200', 14, 2)->default(0.0);
            $table->decimal('fraction_500', 14, 2)->default(0.0);
            $table->decimal('fraction_1000', 14, 2)->default(0.0);
            $table->decimal('fraction_2000', 14, 2)->default(0.0);
            $table->decimal('fraction_5000', 14, 2)->default(0.0);
            $table->decimal('fraction_10000', 14, 2)->default(0.0);
            $table->decimal('fraction_20000', 14, 2)->default(0.0);
            $table->decimal('fraction_50000', 14, 2)->default(0.0);
            $table->decimal('fraction_100000', 14, 2)->default(0.0);
            $table->decimal('clerk_amount_cash', 14, 2)->default(0.0);
            $table->decimal('clerk_amount_non_cash', 14, 2)->default(0.0);
            $table->string('state', 1)->default('U');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('clerk_user_id')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //account_transactions
        Schema::create('account_transactions', function($table) {
            $table->increments('id');
            $table->string('invoice_no', 50)->nullable();
            $table->string('reference_no', 20)->nullable();
            $table->timestamp('input_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->decimal('paid', 14, 2)->default(0.0);
            $table->decimal('due', 14, 2)->default(0.0);
            $table->boolean('status')->default(true);
            $table->string('type', 1)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('paid_date')->nullable();
            $table->string('subject', 255)->nullable();
            $table->date('invoice_date');
            $table->integer('create_by')->unsigned();
            $table->index('create_by');
            $table->string('approved_status', 1)->nullable();
            $table->string('subject_payment', 255)->nullable();
            $table->integer('account_id')->unsigned();
            $table->index('account_id');
            $table->timestamps();
            $table->foreign('create_by')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('account_id')
                ->references('id')
                ->on('account')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //sub_account_trx
        Schema::create('sub_account_trx', function($table) {
            $table->increments('id');
            $table->text('item')->nullable();
            $table->text('description')->nullable();
            $table->decimal('unit_price', 14, 2)->nullable();
            $table->decimal('amount', 14, 2)->nullable();
            $table->integer('account_type_id')->nullable()->unsigned();
            $table->index('account_type_id');
            $table->integer('account_trx_id')->nullable()->unsigned();
            $table->index('account_trx_id');
            $table->string('approved_status', 1);
            $table->text('remarks')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('discount', 14, 2)->default(0.0);
            $table->decimal('tax', 14, 2)->default(0.0);
            $table->boolean('status')->default(true);
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->timestamps();
            $table->foreign('account_type_id')
                ->references('id')
                ->on('account')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('account_trx_id')
                ->references('id')
                ->on('account_transactions')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //conversation_user
        Schema::create('conversation_user', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->index('user_id');
            $table->integer('conversation_id')->unsigned();
            $table->index('conversation_id');
            $table->boolean('deleted');
            $table->boolean('read');
            $table->string('key', 200)->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversation')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //vehicle
        Schema::create('vehicle', function($table) {
            $table->increments('id');
            $table->string('number', 9);
            $table->string('type', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('brand', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('customer_id')->unsigned();
            $table->index('customer_id');
            $table->string('year', 4)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customer')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //memberships
        Schema::create('membership', function($table) {
            $table->increments('id');
            $table->string('number', 50);
            $table->integer('discount_id')->unsigned();
            $table->index('discount_id');
            $table->integer('customer_id')->unsigned();
            $table->index('customer_id');
            $table->integer('vehicle_id')->unsigned();
            $table->index('vehicle_id');
            $table->timestamp('registration_date');
            $table->timestamp('expiry_date');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customer')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('discount_id')
                ->references('id')
                ->on('discount')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicle')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //role_access
        Schema::create('role_access', function($table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->index('role_id');
            $table->integer('access_id')->unsigned();
            $table->index('access_id');
            $table->integer('sequence');
            $table->timestamps();
            $table->foreign('role_id')
                ->references('id')
                ->on('role')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('access_id')
                ->references('id')
                ->on('access')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //service_formula
        Schema::create('service_formula', function($table) {
            $table->increments('id');
            $table->integer('service_id')->unsigned();
            $table->index('service_id');
            $table->integer('configured_by')->unsigned();
            $table->index('configured_by');
            $table->decimal('price', 14, 2)->default(0.0);
            $table->timestamp('date');
            $table->timestamp('expiry_date');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('service_id')
                ->references('id')
                ->on('service')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('configured_by')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //transaction
        Schema::create('transaction', function($table) {
            $table->increments('id');
            $table->string('workorder_no', 30);
            $table->string('invoice_no', 30);
            $table->integer('vehicle_id')->unsigned();
            $table->index('vehicle_id');
            $table->integer('membership_id')->nullable();
//            $table->index('membership_id');
            $table->integer('batch_id')->unsigned();
            $table->index('batch_id');
            $table->timestamp('date');
            $table->timestamp('complete_date')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_method', 1)->nullable();
            $table->string('payment_state', 1)->nullable();
            $table->decimal('amount', 14, 2)->default(0.0);
            $table->decimal('tax_amount', 14, 2)->default(0.0);
            $table->decimal('discount_amount', 14, 2)->default(0.0);
            $table->decimal('paid_amount', 14, 2)->default(0.0);
            $table->string('basket', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamp('sync_time');
            $table->string('status', 1);
            $table->timestamps();
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicle')
                ->on_update('restrict')
                ->on_delete('restrict');
//            $table->foreign('membership_id')
//                ->references('id')
//                ->on('membership')
//                ->on_update('restrict')
//                ->on_delete('restrict');
            $table->foreign('batch_id')
                ->references('id')
                ->on('batch')
                ->on_update('restrict')
                ->on_delete('restrict');
        });


        //transaction_item
         Schema::create('transaction_item', function($table) {
             $table->increments('id');
             $table->integer('item_id')->unsigned();
             $table->index('item_id');
             $table->integer('item_price_id')->unsigned();
             $table->index('item_price_id');
             $table->integer('transaction_id')->unsigned();
             $table->index('transaction_id');
             $table->integer('quantity')->default(0);
             $table->string('description', 255)->nullable();
             $table->timestamps();
             $table->foreign('item_id')
                 ->references('id')
                 ->on('item')
                 ->on_update('restrict')
                 ->on_delete('restrict');
             $table->foreign('item_price_id')
                 ->references('id')
                 ->on('item_price')
                 ->on_update('restrict')
                 ->on_delete('restrict');
             $table->foreign('transaction_id')
                 ->references('id')
                 ->on('transaction')
                 ->on_update('restrict')
                 ->on_delete('restrict');
         });

        //user_workorde
        Schema::create('user_workorder', function($table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned();
            $table->index('transaction_id');
            $table->integer('user_id')->unsigned();
            $table->index('user_id');
            $table->string('description', 255)->nullable();
            $table->timestamps();
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transaction')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //message
        Schema::create('message', function($table) {
            $table->increments('id');
            $table->integer('topic_id');
            $table->integer('user_id')->unsigned();
            $table->index('user_id');
            $table->integer('sender_id')->unsigned();
            $table->index('sender_id');
            $table->boolean('deleted')->default(false);
            $table->boolean('read')->default(false);
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('sender_id')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
        });


        //item stock flow
        Schema::create('item_stock_flow', function($table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->index('item_id');
            $table->integer('account_transaction_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('type', 1)->nullable();
            $table->timestamp('date');
            $table->string('status', 1);
            $table->integer('configured_by')->unsigned();
            $table->index('configured_by');
            $table->integer('sub_account_trx_id')->unsigned();
            $table->index('sub_account_trx_id');
            $table->timestamps();
            $table->foreign('item_id')
                ->references('id')
                ->on('item')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('configured_by')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('sub_account_trx_id')
                ->references('id')
                ->on('sub_account_trx')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //settlement
        Schema::create('settlement', function($table) {
            $table->increments('id');
            $table->decimal('fraction_50', 14, 2)->default(0.0);
            $table->decimal('fraction_100', 14, 2)->default(0.0);
            $table->decimal('fraction_200', 14, 2)->default(0.0);
            $table->decimal('fraction_500', 14, 2)->default(0.0);
            $table->decimal('fraction_1000', 14, 2)->default(0.0);
            $table->decimal('fraction_2000', 14, 2)->default(0.0);
            $table->decimal('fraction_5000', 14, 2)->default(0.0);
            $table->decimal('fraction_10000', 14, 2)->default(0.0);
            $table->decimal('fraction_20000', 14, 2)->default(0.0);
            $table->decimal('fraction_50000', 14, 2)->default(0.0);
            $table->decimal('fraction_100000', 14, 2)->default(0.0);
            $table->decimal('amount', 14, 2)->default(0.0);
            $table->decimal('amount_cash', 14, 2)->default(0.0);
            $table->decimal('amount_non_cash', 14, 2)->default(0.0);
            $table->integer('user_id')->unsigned();
            $table->index('user_id');
            $table->string('state', 1);
            $table->timestamp('settlement_Date', 1);
            $table->boolean('match', 1)->default(false);
            $table->integer('success_transaction')->default(0);
            $table->boolean('status')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //news
         Schema::create('news', function($table) {
             $table->increments('id');
             $table->string('title', 150);
             $table->string('resume', 255)->nullable();
             $table->string('file_path', 100)->nullable();
             $table->text('content')->nullable();
             $table->boolean('status')->default(1);
             $table->timestamps();
         });

        //transaction_service
        Schema::create('transaction_service', function($table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned();
            $table->index('transaction_id');
            $table->integer('service_formula_id')->unsigned();
            $table->index('service_formula_id');
            $table->float('tax')->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->string('description', 255)->nullable();
            $table->timestamps();
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transaction')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('service_formula_id')
                ->references('id')
                ->on('service_formula')
                ->on_update('restrict')
                ->on_delete('restrict');
        });


        //batch_service
        Schema::create('batch_service', function($table) {
            $table->increments('id');
            $table->integer('batch_id')->unsigned();
            $table->index('batch_id');
            $table->integer('service_id')->unsigned();
            $table->index('service_id');
            $table->float('sales_count')->default(0);
            $table->decimal('sales_amount', 14, 2)->default(0);
            $table->timestamps();
            $table->foreign('batch_id')
                ->references('id')
                ->on('batch')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('service_id')
                ->references('id')
                ->on('service')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //batch_item
        Schema::create('batch_item', function($table) {
            $table->increments('id');
            $table->integer('batch_id')->unsigned();
            $table->index('batch_id');
            $table->integer('item_id')->unsigned();
            $table->index('item_id');
            $table->float('sales_count')->default(0);
            $table->decimal('sales_amount', 14, 2)->default(0);
            $table->timestamps();
            $table->foreign('batch_id')
                ->references('id')
                ->on('batch')
                ->on_update('restrict')
                ->on_delete('restrict');
            $table->foreign('item_id')
                ->references('id')
                ->on('item')
                ->on_update('restrict')
                ->on_delete('restrict');
        });

        //asset_type
        Schema::create('asset_type', function($table) {
            $table->increments('id');
            $table->string('name', 80);
            $table->string('description', 255);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });


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
            $table->string('condition', 1)->default('G');;
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
            'access',
            'account',
            'system_properties',
            'conversation',
            'customer',
            'discount',
            'role',
            'service',
            'item_category',
            'unit_type',
            'item_type',
            'item',
            'user',
            'item_price',
            'batch',
            'account_transactions',
            'sub_account_trx',
            'conversation_user',
            'vehicle',
            'membership',
            'role_access',
            'service_formula',
            'transaction',
            'transaction_item',
            'transaction_service',
            'user_workorder',
            'message',
            'item_stock_flow',
            'settlement',
            'news',
            'batch_service',
            'batch_item',
            'asset_type',
            'asset',
        ));
	}

}