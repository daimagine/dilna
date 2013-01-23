<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/18/12
 * Time: 03:56 AM
 * To change this template use File | Settings | File Templates.
 */
class Vehicle extends Eloquent {
	
    public static $table = 'vehicle';
	
    public static $sqlformat = 'Y-m-d H:i:s';
		
	public function customer() {
        return $this->belongs_to('Customer');
    }

    public function membership() {
        return $this->has_one('Member', 'vehicle_id')
            ->where('status','=',1);
    }

	public function get_owner() {
		return $this->customer->name;
	}

    public static function remove($id) {
        $vehicle = Vehicle::find($id);
        $vehicle->status = 0;
        $vehicle->save();
        return $vehicle->id;
    }

    public static function listAll($criteria) {
        return Vehicle::where('status', '=', 1)->get();
    }

	public static function create($data=array()) {

		if($data['customer_id'] == '0')
        return false;

        $vehicle = new Vehicle;
        $vehicle->number = $data['number'];
        $vehicle->customer_id = $data['customer_id'];
		$vehicle->status = $data['status'];
		$vehicle->type = @$data['type'];
		$vehicle->color = @$data['color'];
		$vehicle->model = @$data['model'];
		$vehicle->brand = @$data['brand'];
		$vehicle->description = @$data['description'];
		$vehicle->year = @$data['year'];

		//save
        $vehicle->save();
		return $vehicle->id;
	}


    //===jo edit====//
    public static function getSingleResult($criteria) {
        $v=Vehicle::where('status', '=', 1);
        if(isset($criteria['customer_id'])) {$v=$v->where('customer_id', '=', $criteria['customer_id']);}
        if(isset($criteria['vehicle_number'])) {$v=$v->where('number', '=', $criteria['vehicle_number']);}
        return $v->first();
    }
}