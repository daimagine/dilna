<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/11/12
 * Time: 10:47 PM
 * To change this template use File | Settings | File Templates.
 */
class Role_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'role@index');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        $criteria = array();
        $roles = Role::listAll($criteria);
        return $this->layout->nest('content', 'role.index', array(
            'roles' => $roles
        ));
    }
	
	public function get_detail($id=null) {
		if($id===null) {
			return Redirect::to('role/index');
		}
		$role = Role::find($id);
		$selectedAccess = Role::getAssignedAccess($role);
        return $this->layout->nest('content', 'role.detail', 
			array(
				'role' => $role,
				'access' => $selectedAccess
			)
		);
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('role/index');
        }
        $role = Role::find($id);
        return $this->layout->nest('content', 'role.edit', array('role' => $role));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null   ) {
            return Redirect::to('role/index');
        }
        $role = Role::find($id);
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        $roledata = Input::all();
            if(!$validation->fails()) {
            $success = Role::update($id, $roledata);
            if($success) {
                //success login
                Session::flash('message', 'Success update role');
                return Redirect::to('role/index');
            } else {
                Session::flash('message_error', 'Failed update role');
                return Redirect::to_action('role@edit', array($id));
            }
        } else {
            return Redirect::to_action('role@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
        $roledata = Session::get('role');
        return $this->layout->nest('content', 'role.add', array('role' => $roledata));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $roledata = Input::all();
        if(!$validation->fails()) {
            $success = Role::create($roledata);
            if($success) {
                //success login
                Session::flash('message', 'Success to create new role');
                return Redirect::to('role/index');
            } else {
                Session::flash('message_error', 'Failed to create new role');
                return Redirect::to('role/add');
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('role/add')
                ->with_errors($validation)
                ->with('role', $roledata);
        }
    }

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('role/index');
        }
        $success = Role::remove($id);
        if($success) {
            //success login
                    Session::flash('message', 'Success to remove role');
            return Redirect::to('role/index');
        } else {
            Session::flash('message_error', 'Failed to remove role');
            return Redirect::to('role/index');
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

    public function get_select() {
        Asset::add('jquery.chosen', 'js/plugins/forms/jquery.chosen.min.js', array('jquery', 'jquery-ui'));
        Asset::add('role.application', 'js/role/application.js', array('jquery', 'jquery-ui'));
        $roles = Role::allSelect();
        return $this->layout->nest('content', 'role.select', array('roles' => $roles));
    }

    public function get_access($id=null) {
        if($id===null) {
            return Redirect::to('role/index');
        }
        Asset::add('jquery.chosen', 'js/plugins/forms/jquery.chosen.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.dualListBox', 'js/plugins/forms/jquery.dualListBox.js', array('jquery', 'jquery-ui'));
        Asset::add('role.application', 'js/role/application.js', array('jquery', 'jquery-ui'));
        $role = Role::find($id);
        $availableAccess = Role::getAvailableAccess($role);
        $selectedAccess = Role::getAssignedAccess($role);
        return $this->layout->nest('content', 'role.access', array(
            'role' => $role,
            'availableAccess' => $availableAccess,
            'selectedAccess' => $selectedAccess
        ));
    }

    public function post_access() {
        $id = Input::get('id');
        if($id===null   ) {
            return Redirect::to('role/index');
        }
        $data = Input::all();

        $role = Role::find($id);
        $success = Role::configureAccess($role, $data);
        if($success) {
            //success login
            Session::flash('message', 'Success to save role access');
            return Redirect::to_action('role@access', array( $id ));
        } else {
            Session::flash('message_error', 'Failed to save role access');
            return Redirect::to_action('role@access', array( $id ));
        }
    }

}