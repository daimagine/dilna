<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/18/12
 * Time: 03:35 AM
 */
class Vehicle_Controller extends Secure_Controller {

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
        $vehicle = Vehicle::listAll($criteria);
        return $this->layout->nest('content', 'vehicle.index', array(
            'vehicles' => $vehicle
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('vehicle/index');
        }
		Asset::add('jquery.chosen', 'js/plugins/forms/jquery.chosen.min.js', array('jquery', 'jquery-ui'));
		Asset::add('vehicle.application', 'js/vehicle/application.js', array('jquery', 'jquery-ui'));
        $vehicle = Vehicle::find($id);
		$customers = Customer::getForSelect();
        return $this->layout->nest('content', 'vehicle.edit', array(
            'vehicle' => $vehicle,
			'customers' => $customers
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('vehicle/index');
        }
        $validation = Validator::make(Input::all(), $this->getRules());
        $vehicledata = Input::all();
        if(!$validation->fails()) {
            $success = Vehicle::update($id, $vehicledata);
            if($success) {
                //success edit
                Session::flash('message', 'Success update');
                return Redirect::to('vehicle/index');
            } else {
                Session::flash('message_error', 'Failed update');
                return Redirect::to_action('vehicle@edit', array($id));
            }
        } else {
            return Redirect::to_action('vehicle@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
		Asset::add('jquery.chosen', 'js/plugins/forms/jquery.chosen.min.js', array('jquery', 'jquery-ui'));
		Asset::add('vehicle.application', 'js/vehicle/application.js', array('jquery', 'jquery-ui'));
		$vehicledata = Session::get('vehicle');
		$customers = Customer::getForSelect();
		//dd($customers);
        return $this->layout->nest('content', 'vehicle.add', array(
            'vehicle' => $vehicledata,
			'customers' => $customers
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $vehicledata = Input::all();
        if(!$validation->fails()) {
            $success = Vehicle::create($vehicledata);
            if($success) {
                //success
                Session::flash('message', 'Success create');
                return Redirect::to('vehicle/index');
            } else {
                Session::flash('message_error', 'Failed create');
                return Redirect::to('vehicle/add')
                    ->with('vehicle', $vehicledata);
            }
        } else {
            return Redirect::to('vehicle/add')
                ->with_errors($validation)
                ->with('vehicle', $vehicledata);
        }
    }

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('vehicle/index');
        }
        $success = Vehicle::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('vehicle/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('vehicle/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
			'customer_id' => 'required|min:0',
            'number' => 'required|max:50',
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

}