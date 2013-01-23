<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/21/12
 * Time: 10:06 PM
 * To change this template use File | Settings | File Templates.
 */
class BatchItem extends Eloquent {

    public static $table = 'batch_item';
//    public static $timestamps = false;

    //===jo edit====//
    public static function getSingleResult($criteria) {
        $bi=BatchItem::where('batch_id', '=', $criteria['batch_id']);
        if(isset($criteria['item_id'])){$bi=$bi->where('item_id', '=', $criteria['item_id']);}
        return $bi->first();
    }

    public static function create($data=array()) {
        $bi = new BatchItem();
        $batch = Batch::find($data['batch_id']);
        $bi->batch_id = $batch->id;
        $item = Item::find($data['item_id']);
        $bi->item_id = $item->id;
        $bi->sales_count = 0;
        $bi->sales_amount = 0.00;
        $bi->save();
        return $bi;
    }
}