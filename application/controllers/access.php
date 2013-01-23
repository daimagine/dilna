<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/13/12
 * Time: 12:35 AM
 */
class Access_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'access@index');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        $criteria = array();
        $access = Access::listAll($criteria);
        return $this->layout->nest('content', 'access.index', array(
            'access' => $access
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('access/index');
        }
        $access = Access::find($id);
        $parents = Access::getParents($id);
        return $this->layout->nest('content', 'access.edit', array(
            'access' => $access,
            'parents' => $parents
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('access/index');
        }
        $access = Access::find($id);
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        $accessdata = Input::all();
        if(!$validation->fails()) {
            $success = Access::update($id, $accessdata);
            if($success) {
                //success edit
                Session::flash('message', 'Success update');
                return Redirect::to('access/index');
            } else {
                Session::flash('message_error', 'Failed update');
                return Redirect::to_action('access@edit', array($id));
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to_action('access@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
        $accessdata = Session::get('access');
        $parents = Access::getParents();
        return $this->layout->nest('content', 'access.add', array(
            'access' => $accessdata,
            'parents' => $parents
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $accessdata = Input::all();
        if(!$validation->fails()) {
            $success = Access::create($accessdata);
            if($success) {
                //success
                Session::flash('message', 'Success create');
                return Redirect::to('access/index');
            } else {
                Session::flash('message_error', 'Failed create');
                return Redirect::to('access/add')
                    ->with('access', $accessdata);
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('access/add')
                ->with_errors($validation)
                ->with('access', $accessdata);
        }
    }

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('access/index');
        }
        $success = Access::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('access/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('access/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'name' => 'required|min:3|max:50',
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

