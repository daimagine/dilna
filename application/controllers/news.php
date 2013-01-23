<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/12/12
 * Time: 10:55 PM
 * To change this template use File | Settings | File Templates.
 */
class News_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'news@index');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        $criteria = array();
        $news = News::listAll($criteria);
        Asset::add('news.application', 'js/news/application.js', array('jquery'));
        return $this->layout->nest('content', 'news.index', array(
            'newslist' => $news
        ));
    }

    public function get_all() {
        $criteria = array();
        $news = News::listAll($criteria);
        Asset::add('news.application', 'js/news/application.js', array('jquery'));
        return $this->layout->nest('content', 'news.all', array(
            'newslist' => $news
        ));
    }

    public function get_detail($id=null) {
        if($id===null) {
            return Redirect::to('news/index');
        }
        $news = News::find($id);
        return View::make('news.ajax.detail', array(
            'news' => $news,
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('news/index');
        }
        $news = News::find($id);
        return $this->layout->nest('content', 'news.edit', array(
            'news' => $news,
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('news/index');
        }
        $newsdata = Input::all();
        //dd($newsdata);
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        $newsdata = Input::all();
        if(!$validation->fails()) {
            $success = News::update($id, $newsdata);
            if($success) {
                //success edit
                Session::flash('message', 'Success update');
                return Redirect::to('news/index');
            } else {
                Session::flash('message_error', 'Failed update');
                return Redirect::to_action('news@edit', array($id));
            }
        } else {
            return Redirect::to_action('news@edit', array($id))
                ->with_errors($validation);
        }
    }

    public function get_add() {
        $newsdata = Session::get('news');
        return $this->layout->nest('content', 'news.add', array(
            'news' => $newsdata,
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $newsdata = Input::all();
        //dd($newsdata);
        if(!$validation->fails()) {
            $success = News::create($newsdata);
            if($success) {
                //success
                Session::flash('message', 'Success create');
                return Redirect::to('news/index');
            } else {
                Session::flash('message_error', 'Failed create');
                return Redirect::to('news/add')
                    ->with('news', $newsdata);
            }
        } else {
            return Redirect::to('news/add')
                ->with_errors($validation)
                ->with('news', $newsdata);
        }
    }

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('news/index');
        }
        $success = News::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('news/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('news/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'title' => 'required|min:5|max:150',
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
