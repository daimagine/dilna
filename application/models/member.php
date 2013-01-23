<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/18/12
 * Time: 03:56 AM
 * To change this template use File | Settings | File Templates.
 */
class Member extends Eloquent {

    public static $table = 'membership';
	
    private static $MEMBERSHIP_PREFIX = 'BNACARE';
    private static $MEMBERSHIP_LENGTH = 9;
	
    public static $sqlformat = 'Y-m-d H:i:s';
    public static $format = 'd-m-Y H:i:s';
    public static $dateformat = 'd-m-Y';
    public static $timeformat = 'H:i:s';
	
	public function customer() {
		return $this->belongs_to('Customer');
	}
	
	public function discount() {
		return $this->belongs_to('Discount');
	}

    public function vehicle() {
        return $this->belongs_to('Vehicle');
    }

    public static function member_new() {
        $count = DB::table(static::$table)->order_by('id', 'desc')->take(1)->only('id');
        //dd($count);
        $count++;
        //pad static::$MEMBERSHIP_LENGTH leading zeros
        $suffix = sprintf('%0' . static::$MEMBERSHIP_LENGTH . 'd', $count);
        return static::$MEMBERSHIP_PREFIX . $suffix;
    }
	
    public static function listAll($criteria) {
        return Member::where('status', '=', 1)->get();
    }

    public static function recent() {
        return Member::with(array('vehicle', 'vehicle.customer', 'discount'))
            ->where('status', '=', 1)
            ->order_by('created_at', 'desc')
            ->take(10)
            ->get();
    }

    public static function update($id, $data = array()) {
        $member = Member::where_id($id)
            ->where_status(1)
            ->first();
        $member->number = $data['number'];
        $member->status = $data['status'];
		$member->registration_date = $data['registration_date'];
		$member->expiry_date = $data['expiry_date'];
		
        if(isset($data['discount_id']) && $data['discount_id'] != '0') {
            $member->discount_id = $data['discount_id'];
        }
		
        if(isset($data['customer_id']) && $data['customer_id'] != '0') {
            $member->customer_id = $data['customer_id'];
        }
		
		if(isset($data['register_date']) && $data['register_date'] != null && $data['register_date'] != ''
			&& isset($data['register_time']) && $data['register_time'] != null && $data['register_time'] != '') {
				
			$register_date = $data['register_date'] . $data['register_time'];
			$register_date = DateTime::createFromFormat(static::$format, $register_date);
			$member->registration_date = $register_date->format(static::$sqlformat);
		}
        //save
        $member->save();
        return $member->id;
    }

    public static function create($data = array()) {
        $member = new Member;
        $member->number = $data['number'];
        $member->status = $data['status'];
		$member->registration_date = $data['registration_date'];
		$member->expiry_date = $data['expiry_date'];
		
        if(isset($data['discount_id']) && $data['discount_id'] != '0') {
            $member->discount_id = $data['discount_id'];
        }
		
        if(isset($data['customer_id']) && $data['customer_id'] != '0') {
            $member->customer_id = $data['customer_id'];
        }

        if(isset($data['vehicle_id']) && $data['vehicle_id'] != '0') {
            $member->vehicle_id = $data['vehicle_id'];
        }
		
		if(isset($data['register_date']) && $data['register_date'] != null && $data['register_date'] != ''
			&& isset($data['register_time']) && $data['register_time'] != null && $data['register_time'] != '') {
				
			$register_date = $data['register_date'] . $data['register_time'];
			$register_date = DateTime::createFromFormat(static::$format, $register_date);
			$member->registration_date = $register_date->format(static::$sqlformat);
		}
		//save
        $member->save();
        return $member->id;
    }

    public static function remove($id) {
        $member = Member::find($id);
        $member->status = false;
        return $member->save();
    }
	
	public function get_description() {
		$d = $this->discount;
		$desc  = $d->duration . ' ';
		$desc .= $d->duration_period == 'M' ? 'Month' : ( $d->duration_period == 'Y' ? 'Year' : '' ) . ' ';
		$desc .= '(' . number_format($d->value, 2) . '% discount) - IDR' . $d->registration_fee;
		$discounts[$d->id] = $desc;
		return $desc;
	}

    public static function getSingleResult($criteria) {
        $member = Member::where_in('status', $criteria['status']);
        if (isset($criteria['vehicle_id'])) {$member = $member->where('vehicle_id', '=', $criteria['vehicle_id']);}
        if (isset($criteria['is_member'])){$member = $member->where('expiry_date', '>=', date(static::$sqlformat, time()));}
        return $member->first();
    }


}
