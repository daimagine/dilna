<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/8/12
 * Time: 1:22 AM
 * To change this template use File | Settings | File Templates.
 */

class TransactionItem extends Eloquent {

    public static $table = 'transaction_item';
//    public static $timestamps = false;

    public function item() {
        return $this->belongs_to('Item', 'item_id');
    }

    public function item_price() {
        return $this->belongs_to('ItemPrice', 'item_price_id');
    }

    public static function listById($criteria) {
        $trxItem = TransactionItem::where('transaction_id', '=', $criteria['id']);
        return $trxItem->get();
    }
}