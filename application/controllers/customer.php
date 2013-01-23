<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/18/12
 * Time: 03:35 AM
 */
class Customer_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'customer@index');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        $criteria = array();
        $customer = Customer::allWithMembership($criteria);
        return $this->layout->nest('content', 'customer.index', array(
            'customers' => $customer,
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('customer/index');
        }
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
		Asset::add('customer.application', 'js/customer/application.js', array('jquery.timeentry'));
        
		$customer = Customer::find($id);
		$vehicles = $customer->vehicles;
		$discount_id = 0;
		if(isset($customer->membership) && sizeof($customer->membership) > 0)
			$discount_id = $customer->membership->discount_id;
		
		$allDisc = Discount::listAll();
        $discounts = array( 0 => 'Without Membership' );
        foreach($allDisc as $d) {
            $desc  = $d->duration . ' ';
            $desc .= $d->duration_period == 'M' ? 'Month' : ( $d->duration_period == 'Y' ? 'Year' : '' ) . ' ';
            $desc .= '(' . $d->value . '% discount) - IDR' . $d->registration_fee;
            $discounts[$d->id] = $desc;
        }
	
        return $this->layout->nest('content', 'customer.edit', array(
            'customer' => $customer,
			'vehicles' => $vehicles,
			'discounts' => $discounts,
			'discount_id' => $discount_id
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('customer/index');
        }
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        $memberdata = Input::all();
        //dd($memberdata);
        if(!$validation->fails()) {
            if($this->vehicle_validation($memberdata)) {
                $success = Customer::update($id, $memberdata);
                if($success) {
                    //success edit
                    Session::flash('message', 'Success update');
                    return Redirect::to('customer/index');
                } else {
                    Session::flash('message_error', 'Failed update');
                    return Redirect::to_action('customer@edit', array($id));
                }
            } else {
                Session::flash('message_error', 'Minimum one vehicle is required');
                return Redirect::to_action('customer@edit', array($id));
            }
        } else {
            return Redirect::to_action('customer@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
		Asset::add('customer.application', 'js/customer/application.js', array('jquery.timeentry'));
        $memberdata = Session::get('customer');
        $allDisc = Discount::listAll();
        $discounts = array( 0 => 'Without Membership' );
        foreach($allDisc as $d) {
            $desc  = $d->duration . ' ';
            $desc .= $d->duration_period == 'M' ? 'Month' : ( $d->duration_period == 'Y' ? 'Year' : '' ) . ' ';
            $desc .= '(' . $d->value . '% discount) - IDR' . $d->registration_fee;
            $discounts[$d->id] = $desc;
        }

        return $this->layout->nest('content', 'customer.add', array(
            'customer' => $memberdata,
            'discounts' => $discounts
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $memberdata = Input::all();
//        dd($memberdata);
        if(!$validation->fails()) {
            if($this->vehicle_validation($memberdata)) {
                $success = Customer::create($memberdata);
                if($success) {
                    //success
                    Session::flash('message', 'Success create');
                    return Redirect::to('customer/index');
                } else {
                    Session::flash('message_error', 'Failed create');
                    return Redirect::to('customer/add')
                        ->with('customer', $memberdata);
                }
            } else {
                Session::flash('message_error', 'Minimum one vehicle is required');
                return Redirect::to('customer/add')
                    ->with('customer', $memberdata);
            }
        } else {
            return Redirect::to('customer/add')
                ->with_errors($validation)
                ->with('customer', $memberdata);
        }
    }

    public function get_delete($id=null, $purge=false) {
        if($id===null) {
            return Redirect::to('customer/index');
        }
        if($purge === 'purge')
            $success = Customer::rip($id);
        else
            $success = Customer::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('customer/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('customer/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'name' => 'required|min:3|max:50',
            //'register_date' => 'required',
            //'register_time' => 'required',
        );
        if($method == 'add') {
            $additional = array(
            );
        } elseif($method == 'edit') {
            $additional = array(
            );
        }
        return array_merge($rules, $additional);
    }


    private function vehicle_validation($data) {
        //validate minimum one vehicle
        if(array_key_exists('vehicles', $data) && is_array($data['vehicles']) && sizeof($data['vehicles']) >= 1 )
            return true;
        return false;
    }

}