<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/28/12
 * Time: 2:18 AM
 * To change this template use File | Settings | File Templates.
 */
class Work_Order_Controller extends Secure_Controller
{

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'work_order@index');
    }

    public function get_index() {
        return$this->get_list();
    }

    public function get_list() {
        Asset::add('function_item', 'js/item/confirmation.js',  array('jquery', 'jquery-ui'));
        $transactions = Transaction::list_all(array(
            'status' => array(
                statusWorkOrder::OPEN, statusWorkOrder::CLOSE, statusWorkOrder::CANCELED
            )
        ));

//        {{dd($transactions);}}
        return $this->layout->nest('content', 'wo.list', array(
            'transactions' => $transactions
        ));
    }

    public function get_add() {
        Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
        Asset::add('function_wo', 'js/wo/application.js',  array('jquery', 'jquery.timeentry'));

        $wodata = Session::get('wodata');
        $customer = Session::get('customer');
        $service = Session::get('service');
        $mechanic = Session::get('mechanic');

        //------------get service list---------------------//
        $lstService = Service::list_all(array(
            'status' => array(statusType::ACTIVE)
        ));
        $selectionService = array();
        $selectionService[0] = '-- select service --';
        foreach($lstService as $srv) {
            $selectionService[$srv->id] = $srv->name;
        }

        //------------GET MECHANIC----------------------//
        $lstMechanic=User::listByCiteria(array(
           'role_id' => 3
        ));
        $selectionMechanic = array();
        $selectionMechanic[0] = '-- select mechanic --';
        foreach($lstMechanic as $mch) {
            $selectionMechanic[$mch->id] = $mch->name;
        }


        return $this->layout->nest('content', 'wo.add', array(
            'wodata' => $wodata,
            'customers' => $customer,
            'selectionService' => $selectionService,
            'lstService' => $lstService,
            'service' => $service,
            'selectionMechanic' => $selectionMechanic,
            'mechanic' => $mechanic
        ));
    }

    //================ get for simple transaction =============================
    public function get_add_simple_trx() {
        Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
        Asset::add('function_wo', 'js/wo/application.js',  array('jquery', 'jquery.timeentry'));

        $wodata = Session::get('wodata');
        $customer = Session::get('customer');
        $service = Session::get('service');
        $mechanic = Session::get('mechanic');

        //------------get service list---------------------//
        $lstService = Service::list_all(array(
            'status' => array(statusType::ACTIVE)
        ));
        $selectionService = array();
        $selectionService[0] = '-- select service --';
        foreach($lstService as $srv) {
            $selectionService[$srv->id] = $srv->name;
        }

        //------------GET MECHANIC----------------------//
        $lstMechanic=User::listByCiteria(array(
            'role_id' => 3
        ));
        $selectionMechanic = array();
        $selectionMechanic[0] = '-- select mechanic --';
        foreach($lstMechanic as $mch) {
            $selectionMechanic[$mch->id] = $mch->name;
        }


        return $this->layout->nest('content', 'wo.add_simple_trx', array(
            'wodata' => $wodata,
            'customers' => $customer,
            'selectionService' => $selectionService,
            'lstService' => $lstService,
            'service' => $service,
            'selectionMechanic' => $selectionMechanic,
            'mechanic' => $mechanic
        ));
    }


    private function define_asset(){
        Asset::add('style', 'css/styles.css');
        Asset::add('jquery', 'js/jquery.min.js');
        Asset::add('jquery-ui', 'js/jquery-ui.min.js', array('jquery'));
        Asset::add('jquery-uniform', 'js/plugins/forms/jquery.uniform.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.dataTables', 'js/plugins/tables/jquery.dataTables.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.sortable', 'js/plugins/tables/jquery.sortable.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.resizable', 'js/plugins/tables/jquery.resizable.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.collapsible', 'js/plugins/ui/jquery.collapsible.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.breadcrumbs', 'js/plugins/ui/jquery.breadcrumbs.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.tipsy', 'js/plugins/ui/jquery.tipsy.js', array('jquery', 'jquery-ui'));
        Asset::add('bootstrap-js', 'js/bootstrap.js', array('jquery-uniform'));
        Asset::add('application-js', 'js/application.js', array('jquery-uniform'));
    }

    public function get_lst_customer() {
       //get list customer by vehicle
        Work_Order_Controller::define_asset();
        $lstVehicle = Vehicle::listAll(array());
        return View::make('wo.modal.customer', array(
            'lstVehicle' => $lstVehicle
        ));
    }

    public function get_lst_items() {
        //get list items
        Work_Order_Controller::define_asset();
        $lstItemCategory = ItemCategory::listAll(null);
        $lstItems = Item::listAll(array());
        return View::make('wo.modal.items', array(
            'lstItemCategory' => $lstItemCategory,
            'lstItems' => $lstItems
        ));
    }

    public function post_add(){
        $validation = Validator::make(Input::all(), $this->getRules());
        $wodata = Input::all();
        $trxType = $wodata['transaction-type'];
        $redirectFailedUrl = 'work_order/list';
        if ($trxType == TransactionType::SIMPLE_TRANSACTION) {
            $redirectFailedUrl = 'work_order/add_simple_trx';
        } else if ($trxType == TransactionType::WO_TRANSACTION)  {
            $redirectFailedUrl = 'work_order/add';
        }

        Log::info('customer id  ['.$wodata['customerId'].']');
        Log::info('vehicle id   ['.$wodata['vehiclesid'].']');

        if(!$validation->fails()) {
            //------set customer want register or not------------
            $register = false;
            if(isset($wodata['checkbox-register']) && $wodata['checkbox-register']=='on') {
                $register = true;
                Log::info('register this account..');
            }
            //==check item stock first====
            $msgvalitem=null;
            if(isset($wodata['items'])) {
                foreach($wodata['items'] as $it) {
                    $item = Item::find($it['item_id']);
                    if ($item->stock < ((int)$it['quantity'])) {
                        $msgvalitem .=   'stock item '.($item->name).' not enough, current stock '.($item->stock);
                    }
                }
            }
            if ($msgvalitem !== null){
                Session::flash('message_error', $msgvalitem);
                return Redirect::to($redirectFailedUrl)
                    ->with('wodata',$wodata);
            }

            //=== check status customer ===//
            if($wodata['customerId']==null or $wodata['customerId']=='' or $wodata['customerId']=='0') {
                Log::info('Non customer');
                $customer = null;
                if($register) {
                    Log::info('register new customer');
                    $customer = Customer::create(array(
                        'name' => $wodata['customerName'],
                        'address1' => (isset($wodata['address1']) ? $wodata['address1'] : ''),
                        'address2' => (issset($wodata['address2']) ? $wodata['address2'] : ''),
                        'city' => (isset($wodata['city']) ? $wodata['city'] : ''),
                        'post_code' => (isset($wodata['post_code']) ? $wodata['post_code'] : ''),
                        'phone1' => (isset($wodata['phone1']) ? $wodata['phone1'] : ''),
                        'phone2' => (isset($wodata['phone2']) ? $wodata['phone2'] : ''),
                        'additional_info' => (isset($wodata['additional_info']) ? $wodata['additional_info'] : ''),
                        'status' => statusType::ACTIVE
                    ));
                } else if(!$register) {
                    Log::info('used customer dummy');
                    $customer = 1;
                } else {
                    Log::info('failed save wo');
                    Session::flash('message_error', 'Failed save workorder');
                    return Redirect::to($redirectFailedUrl)
                        ->with('wodata',$wodata);
                }
                $wodata['customerId'] = $customer;
            }

            Log::info('customer_id..'.$wodata['customerId']);
            if($wodata['vehiclesid']==null or $wodata['vehiclesid']=='' or $wodata['vehiclesid']== '0'){
                Log::info('masuk create vehicle..');
                //--------------validation vehicle no (unique Id)-------------
                $checkVehicle = Vehicle::where('number', '=', $wodata['vehiclesnumber'])->first();
                $vehicle = null;
                $dataVehicle = array(
                    'customer_id' => $wodata['customerId'],
                    'status' => statusType::ACTIVE,
                    'number' => $wodata['vehiclesnumber'],
                    'type' => ((isset($wodata['vehiclestype']) ? $wodata['vehiclestype'] : "")),
                    'color' => ((isset($wodata['vehiclescolor']) ? $wodata['vehiclescolor'] : "")),
                    'model' => ((isset($wodata['vehiclesmodel']) ? $wodata['vehiclesmodel'] : "")),
                    'brand' => ((isset($wodata['vehiclesbrand']) ? $wodata['vehiclesbrand'] : "")),
                    'description' => ((isset($wodata['vehiclesdescription']) ? $wodata['vehiclesdescription'] : ""))
                );

                if (!$register && $checkVehicle){
                    Log::info('vehicle found ');
                    $vehicle = $checkVehicle->id;
                } else if($register && !$checkVehicle) {
                    Log::info('create vehicle with register custmer ');
                    $vehicle = Vehicle::create($dataVehicle);
                    Log::info('vehicle id '.$vehicle);
                } else if(!$register && !$checkVehicle) {
                    Log::info('create vehicle without register customer');
                    $vehicle = Vehicle::create($dataVehicle);
                    Log::info('vehicle id '.$vehicle);
                } else if($register && $checkVehicle) {
                    Session::flash('message_error', 'Vehicle No has been register');
                    return Redirect::to($redirectFailedUrl)
                        ->with('wodata',$wodata);
                }

                if($vehicle) {
                    $wodata['vehiclesid'] = $vehicle;
                }
            } else {
                Log::info('get vehicle from database..');
                $vehicle = Vehicle::getSingleResult(array(
                    'customer_id' => $wodata['customerId'],
                    'vehicle_number' => $wodata['vehiclesnumber']
                ));
                $wodata['vehiclesid'] = $vehicle->id;
            }

            Log::info('vehicle id from db ['.$wodata['vehiclesid'].']');
            $success = Transaction::create($wodata);
            if($success) {
                if($trxType == TransactionType::SIMPLE_TRANSACTION) {
                    $update = Transaction::update_status($success, statusWorkOrder::CLOSE, array(
                        'complete_date' => date('Y-m-d H:i:s'),
                        'payment_date' => date('Y-m-d H:i:s'),
                        'payment_method' => @$wodata['payment_method'],
                        'payment_state' => paymentState::DONE,
                    ));
                    Session::flash('message', 'Success add and closed wo'.$wodata['customerName']);
                    return Redirect::to('work_order/to_invoice/'.$success);
                } else {
                    Session::flash('message', 'Success add wo for '.$wodata['customerName']);
                    return Redirect::to('work_order/list');
                }
            } else {
                Session::flash('message_error', 'Failed add wo');
                return Redirect::to($redirectFailedUrl)
                    ->with('wodata',$wodata);
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to($redirectFailedUrl)
                ->with_errors($validation)
                ->with('wodata',$wodata);

        }
    }


    //GET DETAIL
    public function get_detail($id=null){
        if ($id===null) {
            return Redirect::to('work_order/list');
        }
        $action = Input::get('type');
        $transaction = Transaction::get_detail_trx($id);
        return $this->layout->nest('content', 'wo.detail', array(
            'transaction' => $transaction,
            'action' => $action
        ));
    }

    public function post_do_closed(){
        $data = Input::all();
        $id = $data['id'];
        if ($id===null) {
            Session::flash('message_error', 'Failed update wo');
            return Redirect::to('work_order/list');
        }

        //process calculate discount for membership
        $trx = Transaction::find($id);
        $update = Transaction::update_status($id, statusWorkOrder::CLOSE, array(
            'complete_date' => date('Y-m-d H:i:s'),
            'payment_date' => date('Y-m-d H:i:s'),
            'payment_method' => $data['payment_method'],
            'payment_state' => paymentState::DONE,
        ));
        if($update) {

            Session::flash('message', 'Success closed wo '.$update->workorder_no);
            return Redirect::to('work_order/list');
        } else {
            Session::flash('message_error', 'Failed closed wo');
            return Redirect::to('work_order/add');
        }
    }

    public function get_do_canceled($id=null){
        if ($id===null) {
            return Redirect::to('work_order/list');
        }
        $update = Transaction::update_status($id, statusWorkOrder::CANCELED, array(
            'payment_state' => paymentState::CANCELED
        ));
        $trxItem = TransactionItem::listById(array(
           'id' => $update->id
        ));
        foreach($trxItem as $trx_item) {
            $item_price = ItemPrice::getSingleResult(array('item_id' => $trx_item->item_id));
            $stock=($item_price->item->stock + $trx_item->quantity);
            $updatestock = Item::updateStock($item_price->item->id, $stock);
        }
        //cleanup items quantity
        $affected = DB::table('transaction_item')
            ->where('transaction_id', '=', $id)
            ->update(array('quantity' => 0));
        if($update) {
            //success
            Session::flash('message', 'Success Canceled wo '.$update->workorder_no);
            return Redirect::to('work_order/list');
        } else {
            Session::flash('message_error', 'Failed canceled wo');
            return Redirect::to('work_order/add');
        }
    }



    //GET UPDATE or EDIT
    public function get_edit($id=null){
        if ($id===null) {
            return Redirect::to('work_order/list');
        }
        Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
        Asset::add('function_wo', 'js/wo/application.js',  array('jquery', 'jquery.timeentry'));

        $transaction = Transaction::get_detail_trx($id);
//        {{dd($transaction);}}
        //------------get service list---------------------//
        $lstService = Service::list_all(array(
            'status' => array(statusType::ACTIVE)
        ));
        $selectionService = array();
        $selectionService[0] = '-- select service --';
        foreach($lstService as $srv) {
            $selectionService[$srv->id] = $srv->name;
        }

        //------------GET MECHANIC----------------------//
        $lstMechanic=User::listByCiteria(array(
            'role_id' => 3
        ));

        $selectionMechanic = array();
        $selectionMechanic[0] = '-- select mechanic --';
        foreach($lstMechanic as $mch) {
            $selectionMechanic[$mch->id] = $mch->name;
        }


        return $this->layout->nest('content', 'wo.update', array(
            'selectionService' => $selectionService,
            'lstService' => $lstService,
            'selectionMechanic' => $selectionMechanic,
            'transaction' => $transaction

        ));
    }


    public function post_edit(){
        $validation = Validator::make(Input::all(), $this->getRules());
        $wodata = Input::all();
        $id = $wodata['id'];
        if ($id === null) {
            return Redirect::to('work_order/list');
        }
//        $serviceFormulaId=array();
//        $no=0;
//        foreach($wodata['services'] as $s){
//            $serviceFormulaId[$no] = $s['service_formula_id'];
//            $no++;
//        }
//        {{dd($wodata);}}
        if(!$validation->fails()) {
            //=== check status customer ===//
            if($wodata['customerId']==null or $wodata['customerId']==''){
                //thisis new customer & new vehicle
                $customer = Customer::create(array(
                    'name' => $wodata['customerName'],
                    'status' => 1
                ));
                $wodata['customerId'] = $customer;
            }

            if($wodata['vehiclesid']==null or $wodata['vehiclesid']=='' or $wodata['vehiclesid']==0){
                $vehicle = Vehicle::create(array(
                    'customer_id' => $wodata['customerId'],
                    'status' => statusType::ACTIVE,
                    'number' => $wodata['vehiclesnumber'],
                    'type' => $wodata['vehiclestype'],
                    'color' => $wodata['vehiclescolor'],
                    'model' => $wodata['vehiclesmodel'],
                    'brand' => $wodata['vehiclesbrand'],
                    'description' => $wodata['vehiclesdescription']
                ));

                if($vehicle) {
                    //success create new vehicle
                    $wodata['vehiclesid'] = $vehicle;
                }
            } else {
                $vehicle = Vehicle::getSingleResult(array(
                    'customer_id' => $wodata['customerId'],
                    'vehicle_number' => $wodata['vehiclesnumber']
                ));//TEMPORARY MAKE SURE KE ADI RELASI CUSTOMER DGN VEHICLE (1 to 1 / 1 to m)
                $wodata['vehiclesid'] = $vehicle->id;
            }

            $success = Transaction::update($id, $wodata);
            if($success) {
                //success
                Session::flash('message', 'Success update wo');
                return Redirect::to('work_order/list');
            } else {
                Session::flash('message_error', 'Failed update wo');
                return Redirect::to('work_order/edit/'.$id);
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('work_order/edit/'.$id)
                ->with_errors($validation);
        }
    }

    //GET DETAIL
    public function get_to_invoice($id=null){
        if ($id===null) {
            return Redirect::to('work_order/list');
        }
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/wo/application.js',  array('jquery', 'jquery-ui'));
        $action = Input::get('type');
        $transaction = Transaction::get_detail_trx($id);
        return $this->layout->nest('content', 'wo.to_invoice', array(
            'transaction' => $transaction,
            'action' => $action
        ));
    }

    public function get_print_wo($id=null){
        if ($id===null) {
            return Redirect::to('work_order/list');
        }
        Asset::add('style', 'css/print/style.css');
        Asset::add('print', 'css/print/print.css');
        Asset::add('jquery', 'js/wo/print/jquery-1.3.2.min.js');
        Asset::add('exemple', 'js/wo/print/example.js', array('jquery'));
        $action = Input::get('type');
        $transaction = Transaction::get_detail_trx($id);
        return View::make('wo.print_wo', array(
            'transaction' => $transaction,
            'action' => $action
        ));
    }


    public function get_print_invoice($id=null){
        if ($id===null) {
            return Redirect::to('work_order/list');
        }
        Asset::add('style', 'css/print/style.css');
        Asset::add('print', 'css/print/print.css');
        Asset::add('jquery', 'js/wo/print/jquery-1.3.2.min.js');
        Asset::add('exemple', 'js/wo/print/example.js', array('jquery'));
        $action = Input::get('type');
        $transaction = Transaction::get_detail_trx($id);
        return View::make('wo.print_invoice', array(
            'transaction' => $transaction,
            'action' => $action
        ));
    }

    //=======================RULES INPUT============================//
    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'customerName' => 'required|max:50',
            'vehiclesid' => 'required',
            'services' => 'required',
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


    public function post_item_print(){
        $wodata = Input::all();
        {{dd($wodata);}}
    }

}
