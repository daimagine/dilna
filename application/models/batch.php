<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/20/12
 * Time: 9:51 PM
 * To change this template use File | Settings | File Templates.
 */
class Batch extends Eloquent {

    public static $table = 'batch';
    private static $sqlformat = 'Y-m-d H:i:s';

    //===jo edit====//
    public static function getSingleResult($criteria) {
        $b=Batch::where_in('batch_status', $criteria['status']);
        return $b->first();
    }

    public static function create($data=array()) {
        $b = new Batch();
        $b->batch_status = batchStatus::UNSETTLED;
        $b->open_time = date(static::$sqlformat);
        $b->sales_count = 0;
        $b->sales_amount = 0.00;
        $b->clerk_amount = 0.00;
        $b->clerk_status = statusType::ACTIVE;
        $b->state = SettlementState::UNSETTLED;
        $b->save();
        return $b;
    }
}
