<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/29/12
 * Time: 11:36 PM
 * To change this template use File | Settings | File Templates.
 */

class ServiceFormula extends Eloquent {

    public static $table = 'service_formula';

    public function service() {
        return $this->belongs_to('Service', 'service_id');
    }

    public function user() {
        return $this->belongs_to('User', 'configured_by');
    }

    public static function list_all($criteria) {
        $serFor = ServiceFormula::with('service', 'user')->where_in('status', $criteria['status']);
        $serFor=$serFor->get();
        return $serFor;
    }

    public static function get_singleResult($criteria) {
        $serFor = ServiceFormula::where_in('status', $criteria['status']);
        if(isset($criteria['service_id'])) {$serFor = $serFor->where('service_id', '=', $criteria['service_id']);}
        $serFor=$serFor->first();
        return $serFor;
    }

    public static function create($data=array()) {
        $serFor = new ServiceFormula();
        $service = Service::find($data['service_id']);
        $serFor->service_id = $service->id;
        $serFor->price = $data['price'];
        $serFor->status = statusType::ACTIVE; //when create status always active
        $serFor->configured_by = Auth::user()->id;
        $serFor->save();
        return $serFor->id;
    }

    public static function update($id, $data=array()) {
        $serFor = ServiceFormula::find($id);
        $service = Service::find($data['service_id']);
        $serFor->service_id = $service->id;
        $serFor->price = $data['price'];
        $serFor->status =  $data['price'];
        $serFor->configured_by = Auth::user()->id;
        $serFor->save();
        return $serFor->id;
    }


    public static function remove($id) {
        $serFor =ServiceFormula::find($id);
        $serFor->status = statusType::INACTIVE;
        $serFor->save();
        return $serFor->id;
    }
}