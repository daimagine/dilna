<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/25/12
 * Time: 12:29 AM
 * To change this template use File | Settings | File Templates.
 */

class Conversation_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'conversation@list');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        $conversations = Conversation::listAll();
        Asset::add('jquery.chosen', 'js/plugins/forms/jquery.chosen.min.js', array('jquery'));
        Asset::add('jquery.chosen.ajaxaddition', 'js/plugins/forms/jquery.chosen.ajaxaddition.js', array('jquery.chosen'));
        Asset::add('conversation.application', 'js/conversation/application.js', array('jquery'));
        return $this->layout->nest('content', 'conversation.list', array(
            'conversations' => $conversations,
        ));
    }

    public function get_new() {
        Asset::add('jquery.chosen', 'js/plugins/forms/jquery.chosen.min.js', array('jquery'));
        Asset::add('jquery.chosen.ajaxaddition', 'js/plugins/forms/jquery.chosen.ajaxaddition.js', array('jquery.chosen'));
        Asset::add('conversation.application', 'js/conversation/application.js', array('jquery'));
        return $this->layout->nest('content', 'conversation.new', array());
    }

    public function get_view($id = null) {
        if($id == null)
            return Redirect::to_action('conversation@list');

        $conversation = Conversation::find($id);
        $conversation->markRead(Auth::user()->id);
        Asset::add('jquery.chosen', 'js/plugins/forms/jquery.chosen.min.js', array('jquery'));
        Asset::add('jquery.chosen.ajaxaddition', 'js/plugins/forms/jquery.chosen.ajaxaddition.js', array('jquery.chosen'));
        Asset::add('conversation.application', 'js/conversation/application.js', array('jquery'));
        return $this->layout->nest('content', 'conversation.detail', array(
            'conversation' => $conversation
        ));
    }

    public function post_assign() {

        return Redirect::to_action('conversation@list');
    }

    public function post_send() {
        $returnurl = 'conversation@new';
        $inputs = Input::all();
        //dd($inputs);
        $validation = Validator::make($inputs, array(
            'message' => 'required|min:3'
        ));
        if(!$validation->fails()) {
            if(array_key_exists('user', $inputs) && is_array($inputs['user']) && sizeof($inputs['user']) > 0) {

                foreach($inputs['user'] as $u) {
                    $temp[] = intval($u);
                }
                $inputs['user'] = $temp;

                $inputs['sender'] = Auth::user()->id;
                //dd($inputs);

                $conv = Conversation::send($inputs);
                if($conv === false) {
                    Session::flash('message_error', 'Conversation cannot be sent. Please try again');

                } else {
                    Session::flash('message', 'Your message has been sent');
                    $returnurl = 'conversation@list';
                    Input::clear();
                }

            } else {
                    Session::flash('message_error', 'Please select at least one message receiver');
            }
        }

        $param = array();
        if(array_key_exists('return_url', $inputs)) {
            if($inputs['return_url'] == 'reply') {
                array_push($param, @$conv->id);
                $returnurl = 'conversation@view';
            }
        }
        return Redirect::to_action($returnurl, $param)
            ->with_errors($validation)
            ->with_input();
    }

}
