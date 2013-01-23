<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/21/12
 * Time: 10:03 PM
 * To change this template use File | Settings | File Templates.
 */
class BatchService extends Eloquent {

    public static $table = 'batch_service';
//    public static $timestamps = false;

    //===jo edit====//
    public static function getSingleResult($criteria) {
        $bs=BatchService::where('batch_id', '=', $criteria['batch_id']);
        if(isset($criteria['service_id'])){$bs=$bs->where('service_id', '=', $criteria['service_id']);}
            return $bs->first();
    }

    public static function create($data=array()) {
        $bs = new BatchService();
        $batch = Batch::find($data['batch_id']);
        $bs->batch_id = $batch->id;
        $service = Service::find($data['service_id']);
        $bs->service_id = $service->id;
        $bs->sales_count = 0;
        $bs->sales_amount = 0.00;
        $bs->save();
        return $bs;
    }
}