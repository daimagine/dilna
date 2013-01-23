<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/18/12
 * Time: 03:35 AM
 */
class Discount_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'member@index');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        $criteria = array();
        $discount = Discount::listAll($criteria);
        return $this->layout->nest('content', 'discount.index', array(
            'discount' => $discount
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('discount/index');
        }
		Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
		Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
		Asset::add('discount.application', 'js/discount/application.js', array('jquery.ui.spinner', 'jquery.ui.mousewheel'));
        $discount = Discount::find($id);
        return $this->layout->nest('content', 'discount.edit', array(
            'discount' => $discount,
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('discount/index');
        }
        $discountdata = Input::all();
		//dd($discountdata);
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        $discountdata = Input::all();
        if(!$validation->fails()) {
            $success = Discount::update($id, $discountdata);
            if($success) {
                //success edit
                Session::flash('message', 'Success update');
                return Redirect::to('discount/index');
            } else {
                Session::flash('message_error', 'Failed update');
                return Redirect::to_action('discount@edit', array($id));
            }
        } else {
            return Redirect::to_action('discount@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
		Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
		Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
		Asset::add('discount.application', 'js/discount/application.js', array('jquery.ui.spinner', 'jquery.ui.mousewheel'));
        $discountdata = Session::get('discount');
        $discount_code = Discount::create_discount_code();
        return $this->layout->nest('content', 'discount.add', array(
            'discount' => $discountdata,
            'discount_code' => $discount_code
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $discountdata = Input::all();
        if(!$validation->fails()) {
            $success = Discount::create($discountdata);
            if($success) {
                //success
                Session::flash('message', 'Success create');
                return Redirect::to('discount/index');
            } else {
                Session::flash('message_error', 'Failed create');
                return Redirect::to('discount/add')
                    ->with('discount', $discountdata);
            }
        } else {
            return Redirect::to('discount/add')
                ->with_errors($validation)
                ->with('discount', $discountdata);
        }
    }

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('discount/index');
        }
        $success = Discount::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('discount/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('discount/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'code' => 'required|min:5|max:50',
            'registration_fee' => 'required|numeric',
            'value' => 'required|min:0'
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