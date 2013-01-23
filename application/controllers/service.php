<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/28/12
 * Time: 2:30 AM
 * To change this template use File | Settings | File Templates.
 */

class Service_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'service@index');
    }


    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
        Asset::add('function_item', 'js/confirmation-delete.js',  array('jquery', 'jquery-ui'));
        $criteria = array(
            'status' => array(statusType::ACTIVE, statusType::INACTIVE)
        );
        $lstService = Service::list_all($criteria);

        return $this->layout->nest('content', 'service.index', array(
            'lstService' => $lstService
        ));
    }

    public function get_add() {
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/service/application.js',  array('jquery', 'jquery-ui'));
        $service = Session::get('service');
        return $this->layout->nest('content', 'service.add', array(
            'service' => $service
        ));

    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $inputData=Input::all();
        if(!$validation->fails()) {
            $storeService = Service::create($inputData);
            if($storeService) {
                $storeSerFor = ServiceFormula::create(array(
                    'service_id' => $storeService,
                    'price' => $inputData['price']
                ));
                if($storeSerFor) {
                    Session::flash('message', 'Success add service');
                    return Redirect::to('service/index');
                }
            }
            Session::flash('message_error', 'Failed add service');
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
        }
        return Redirect::to('service/add')
            ->with_errors($validation)
            ->with('service', $inputData);
    }

    public function get_history() {
        $criteria = array(
            'status' => array(statusType::ACTIVE, statusType::INACTIVE)
        );
        $lstSerFor = ServiceFormula::list_all($criteria);

        return $this->layout->nest('content', 'service.history', array(
            'lstSerFor' => $lstSerFor
        ));
    }

    public function post_history() {

    }

    public function get_edit($id=null) {
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/service/application.js',  array('jquery', 'jquery-ui'));
        if($id===null) {
            return Redirect::to('service/index');
        }

        $service = Service::find($id);
        if ($service === null) {
            return Redirect::to('service/index');
        }
        return $this->layout->nest('content', 'service.edit', array(
            'service' => $service
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            Session::flash('message_error', 'Failed update');
            return Redirect::to('item/index');
        }

        $dataEdit = Input::all();
        $validation = Validator::make(Input::all(), $this->getRules());
        if(!$validation->fails()) {
            $service = Service::update($id, $dataEdit);
            if ($service) {
                $oldSerFor = ServiceFormula::get_singleResult(array(
                    'service_id' => $service,
                    'status' => array(statusType::ACTIVE)
                ));

//                {{dd($oldSerFor);}}
                if ($oldSerFor->price != $dataEdit['price']) {
                    $inactiveOldSerFor = ServiceFormula::remove($oldSerFor->id);
                    $createNewSerFor = ServiceFormula::create(array(
                        'service_id' => $service,
                        'price' => $dataEdit['price']
                    ));

                    if ($inactiveOldSerFor && $createNewSerFor) {
                        Session::flash('message', 'Success update');
                    } else {
                        Session::flash('message_error', 'Failed update Price');
                    }
                } else {
                    Session::flash('message', 'Success update');
                }
            } else {
                Session::flash('message_error', 'Failed update Service');
            }
        } else {
            return Redirect::to('service/add')
                ->with('service', $dataEdit);
        }

        return Redirect::to('service/index');
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'name' => 'required|max:50',
            'description' => 'required',
            'price' => 'required|match:/[0-9]+(\.[0-9][0-9]?)?/',
            'status' => 'required|min:1|max:1'
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

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('service/index');
        }

        $update=Service::remove($id);
        if ($update) {
            Session::flash('message', 'Success inactive');
        } else {
            Session::flash('message_error', 'Failed inactive Service');
        }
        return Redirect::to('service/index');


    }
}