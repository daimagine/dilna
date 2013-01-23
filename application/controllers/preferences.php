<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/14/12
 * Time: 12:00 AM
 * To change this template use File | Settings | File Templates.
 */
class Preferences_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'preferences@index');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        Asset::add('preferences.application', 'js/preferences/application.js', array('jquery'));
        return $this->layout->nest('content', 'preferences.index', array(

        ));
    }

    public function post_change_password() {
        $data = Input::all();
        //dd($data);
        $validation = Validator::make(Input::all(), array(
            'password' => 'required|min:5|max:50',
            'new_password' => 'required|min:5|max:50|confirmed'
        ));
        if(!$validation->fails()) {
            $user = Auth::user();
            $success = User::update_password($user, $data);
            if($success) {
                //success login
                Session::flash('message', 'Change password is success');
                return Redirect::to('preferences/list');
            } else {
                Session::flash('message_error', 'Change password is failed');
                return Redirect::to('preferences/list');
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('preferences/list')
                ->with_errors($validation);
        }
    }

}
