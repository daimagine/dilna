<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/10/12
 * Time: 1:34 AM
 * To change this template use File | Settings | File Templates.
 */

class User_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        $this->filters = array();
        $this->filter('before', 'auth')->except(array('login', 'logout'));
        Session::put('active.main.nav', 'user@index');
    }

    public function get_login() {
        if (Auth::check()) {
            return Redirect::to('/');
        }
        Asset::add('login', 'js/login.js', array('jquery', 'jquery-ui'));
        return View::make('user.login');
    }

    public function post_login() {
        if (Auth::check()) {
            return Redirect::to('/');
        }
        $rules = array(
            'login' => 'required|max:50',
            'password' => 'required|max:50'
        );
        $validation = Validator::make(Input::all(), $rules);
        if(!$validation->fails()) {
            $userdata = array(
                'username' => Input::get('login'),
                'password' => Input::get('password')
            );

            if(Auth::attempt($userdata)) {
                //success login
                return Redirect::to('/')
                    ->with('message', "Login success. Welcome, " . Auth::user()->name);
            } else {
                return Redirect::to('login')
                    ->with('message_error', 'Failed to authenticate');
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('login')->with_errors($validation);
        }
    }

    public function get_logout() {
        $username = Auth::user()->name;
        Auth::logout();
        return Redirect::to('login')
            ->with('message', "Logout success");;
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        $criteria = array();
        $users = User::listAll($criteria);
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.application.user', 'js/user/application.js',  array('jquery', 'jquery-ui'));
        return $this->layout->nest('content', 'user.index', array(
            'users' => $users
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('user/index');
        }
        $user = User::find($id);
        $roles = Role::allSelect();
        return $this->layout->nest('content', 'user.edit', array(
            'roles' => $roles,
            'user'=> $user));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('user/index');
        }
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        $user = User::find($id);
        $userdata = Input::all();
//        {dd($user->picture );}
        if(!$validation->fails()) {
            if(User::unique_login_id($userdata['login_id'], $id)) {
                if (Input::file('picture.name') != null && Input::file('picture.name') != '' ) {
                    //===clean prev image====
                    if ($user->picture != null && $user->picture != '') {
                        File::delete('../backend/images/uploads/user'.$user->picture);
                    }
                    //===uploading image=====
                    $filename = Str::random(32) . '.' . File::extension(Input::file('picture.name'));
                    Input::upload('picture', '../backend/images/uploads/user', $filename);
                    $userdata['picture'] = $filename;
                } else {
                    $userdata['picture']='';
                }

                $success = User::update($id, $userdata);
                if($success) {
                    //success login
                    Session::flash('message', 'Success update user');
                    return Redirect::to('user/index');
                } else {
                    Session::flash('message_error', 'Failed to update user');
                    return Redirect::to_action('user@edit', array($id));
                }
            } else {
                Session::flash('message_error', 'Login Id is already in use');
                return Redirect::to_action('user@edit', array($id));
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to_action('user@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
        $userdata = Session::get('user');
        $roles = Role::allSelect();
        $staff_id = User::generate_staff_id();
        return $this->layout->nest('content', 'user.add', array(
            'roles' => $roles,
            'user' => $userdata,
            'staff_id' => $staff_id
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $userdata = Input::all();
        if(!$validation->fails()) {
            if(User::unique_login_id($userdata['login_id'])) {
                if (Input::file('picture.name') != null && Input::file('picture.name') != '' ) {
                    //===uploading image=====
                    $filename = Str::random(32) . '.' . File::extension(Input::file('picture.name'));
                    Input::upload('picture', '../backend/images/uploads/user', $filename);
                    $userdata['picture'] = $filename;
                } else {
                    $userdata['picture']='';
                }
                $success = User::create($userdata);
                if($success) {
                    //success login
                    Session::flash('message', 'Success to create new user');
                    return Redirect::to('user/index');
                } else {
                    Session::flash('message_error', 'Failed to create new user');
                    return Redirect::to('user/add')
                        ->with_input();
                }
            } else {
                Session::flash('message_error', 'Login Id is already in use');
                return Redirect::to('user/add')
                    ->with_input();
            }
        } else {
            //dd($userdata);
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('user/add')
                ->with_errors($validation)
                ->with_input();
        }
    }

    public function get_delete($id=null, $purge=false) {
        if($id===null) {
            return Redirect::to('user/index');
        }
        $not = 'remove';
        if($purge === 'purge') {
            $success = User::find($id)->delete();
        } else {
            $not = 'inactivate';
            $success = User::remove($id);
        }
        if($success) {
            //success
            Session::flash('message', 'Success to ' . $not . ' user');
            return Redirect::to('user/index');
        } else {
            Session::flash('message_error', 'Failed to ' . $not . ' user');
            return Redirect::to('user/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'photo_profile'     => 'mimes:jpg,gif,png|image|max:100',
            'login_id' 	=> 'required|min:5|max:50|alpha_dash',
            'name'		=> 'required|min:3|max:50',
            'phone1' 	=> 'required|min:12|max:18',
            'status' 	=> 'required',
        );
        if($method == 'add') {
            $additional = array(
                'password' => 'required|min:5|max:50|confirmed'
            );
        } elseif($method == 'edit') {
            $additional = array(
            );
        }
        return array_merge($rules, $additional);
    }

    public function get_find() {
        $name = trim($_GET['data']['q']);
        //dd($name);
        $users = User::find_name_ajax(Auth::user(), $name);
        $result =  json_encode(array('q' => $name, 'results' => $users));
        return $result;
    }


    public function post_update_password() {
        $validation = Validator::make(Input::all(), array(
            'id' => 'required',
            'password' => 'required|min:5|max:50|confirmed'
        ));
        $all_input = Input::all();
        if(!$validation->fails()) {
            $user = User::find($all_input['id']);
            $success = User::update($user->id, array(
                'password' => $all_input['password']
            ));
            if ($success) {
                Session::flash('message', 'Success update password user '.$user->name);
                return Redirect::to('user/index');
            } else {
                Session::flash('message_error', 'Failed update password user '.$user->name);
                return Redirect::to('user/index');
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('user/index')
                ->with_errors($validation)
                ->with_input();
        }
    }

}
