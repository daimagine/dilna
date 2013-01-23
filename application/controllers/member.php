<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/18/12
 * Time: 03:35 AM
 */
class Member_Controller extends Secure_Controller {

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
        $member = Customer::where_status(true)->get();
        //dd($member);
		$allDisc = Discount::listAll();
		$discounts = array();
		foreach($allDisc as $d) {
			$desc  = $d->duration . ' ';
			$desc .= $d->duration_period == 'M' ? 'Month' : ( $d->duration_period == 'Y' ? 'Year' : '' ) . ' ';
			$desc .= '(' . number_format($d->value, 2) . '% discount) - IDR' . $d->registration_fee;
			$discounts[$d->id] = $desc;
		}
        Asset::add('member.application', 'js/member/application.js', array('jquery'));
        return $this->layout->nest('content', 'member.index', array(
            'member' => $member,
			'discounts' => $discounts
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('member/index');
        }
        $member = Member::find($id);
        return $this->layout->nest('content', 'member.edit', array(
            'member' => $member,
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('member/index');
        }
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        $memberdata = Input::all();
        if(!$validation->fails()) {
            $success = Member::update($id, $memberdata);
            if($success) {
                //success edit
                Session::flash('message', 'Success update');
                return Redirect::to('member/index');
            } else {
                Session::flash('message_error', 'Failed update');
                return Redirect::to_action('member@edit', array($id));
            }
        } else {
            return Redirect::to_action('member@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
        $memberdata = Session::get('member');
        return $this->layout->nest('content', 'member.add', array(
            'member' => $memberdata,
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $memberdata = Input::all();
        if(!$validation->fails()) {
            $success = Member::create($memberdata);
            if($success) {
                //success
                Session::flash('message', 'Success create');
                return Redirect::to('member/index');
            } else {
                Session::flash('message_error', 'Failed create');
                return Redirect::to('member/add')
                    ->with('member', $memberdata);
            }
        } else {
            return Redirect::to('member/add')
                ->with_errors($validation)
                ->with('member', $memberdata);
        }
    }

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('member/index');
        }
        $success = Member::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('member/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('member/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'code' => 'required|min:5|max:50',

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
	
	public function post_assign() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('member/index');
        }
        $memberdata = Input::all();
        //dd($memberdata);
        $success = Customer::updateMembership($id, $memberdata);
        if($success) {
            //success edit
            Session::flash('message', 'Assign membership is success');
            return Redirect::to('member/index');
        } else {
            Session::flash('message_error', 'Failed to assign membership');
            return Redirect::to('member/index')
                ->with('id', $id);
        }
    }

    public function get_detail($id=null) {
        if($id===null) {
            return Redirect::to('member/index');
        }
        $member = Member::find($id);
        return View::make('member.ajax.detail', array(
            'member' => $member,
        ));
    }

}