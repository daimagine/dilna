<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 11/4/12
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */
class Report_Transaction_Controller extends Secure_Controller {

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'report@dashboard@index');
    }

    public function action_index() {
        return $this->layout->nest('content', 'report.transaction.index', array());
    }

    public function action_list() {
        //dd(Input::all());

        $startdate = Input::get('startdate');
        $enddate = Input::get('enddate');
        if($startdate == null)
            $startdate = date('d-m-Y', strtotime('09/01/2012'));
        if($enddate == null)
            $enddate = date('d-m-Y');
        $tempdate = DateTime::createFromFormat('d-m-Y H:i:s', $startdate.' 00:00:00');
        $start = $tempdate->format('Y-m-d H:i:s');
        $tempdate = DateTime::createFromFormat('d-m-Y H:i:s', $enddate.' 23:59:59');
        $end = $tempdate->format('Y-m-d H:i:s');

        $criteria = array(
            'date' => array( 'between', $start, $end )
        );
        if(Input::get('wo_status')!==null && Input::get('wo_status')!='') {
            $wo_status = Input::get('wo_status');
            $criteria['wo_status'] = array( '=', $wo_status );
        }
        if(Input::get('customer_name')!==null && Input::get('customer_name')!='') {
            $customer_name = Input::get('customer_name');
            $criteria['customer_name'] = array( 'like', $customer_name );
        }
        if(Input::get('vehicle_no')!==null && Input::get('vehicle_no')!='') {
            $vehicle_no = Input::get('vehicle_no');
            $criteria['vehicle_no'] = array( 'like', $vehicle_no );
        }
        if(Input::get('wo_id')!==null && Input::get('wo_id')!='') {
            $wo_id = Input::get('wo_id');
            $criteria['wo_id'] = array( 'like', $wo_id );
        }
        if(Input::get('invoice_no')!==null && Input::get('invoice_no')!='') {
            $invoice_no = Input::get('invoice_no');
            $criteria['invoice_no'] = array( 'like', $invoice_no );
        }

        $transactions = Transaction::list_report($criteria);

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.transaction.application', 'js/report/transaction/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.transaction.list', array(
            'transactions' => $transactions,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'wo_status' => @$wo_status,
            'customer_name' => @$customer_name,
            'vehicle_no' => @$vehicle_no,
            'wo_id' => @$wo_id,
            'invoice_no' => @$invoice_no,
        ));

    }


    public function action_detail($transaction_id=null) {
//        dd(Input::all());

//        dd($transaction_id);
        if($transaction_id===null) {
            return Redirect::to('report/transaction/list');
        }
        $criteria = array(
            'transaction_id' => array( '=', $transaction_id )
        );
        if(Input::get('item_type')!==null && Input::get('item_type')!='') {
            $item_type = Input::get('item_type');
            $criteria['item_type'] = array( '=', $item_type );
        }
        if(Input::get('item_name')!==null && Input::get('item_name')!='') {
            $item_name = Input::get('item_name');
            $criteria['item_name'] = array( 'like', $item_name );
        }

        $transaction = Transaction::detail_report($criteria);

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.transaction.application', 'js/report/transaction/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.transaction.detail', array(
            'transaction' => $transaction,
            'transaction_id' => $transaction_id,
            'item_type' => @$item_type,
            'item_name' => @$item_name,
        ));
    }

}
