<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/21/12
 * Time: 5:07 AM
 * To change this template use File | Settings | File Templates.
 */
class Settlement_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'settlement@index');
    }

    public function get_index() {
        return Redirect::to_action('settlement@list');
    }

    public function get_list() {
        $settlements = Settlement::listAll();
        //dd($settlements);
        return $this->layout->nest('content', 'settlement.index', array(
           'settlements' => $settlements
        ));
    }

    public function get_edit($id=null) {
        $id !== null ? $id : Input::get('id');
        if($id===null) {
            return Redirect::to('settlement/index');
        }
        $settlement = Settlement::find($id);
        Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
        Asset::add('settlement.application', 'js/settlement/application.js', array('jquery.timeentry'));

//        $date = date(Settlement::$sql_date_format, strtotime($settlement->settlement_date));
//        $bod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 00:00:00'));
//        $eod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 23:59:59'));
//        Log::info('bod : ' . $bod);
//        Log::info('eod : ' .$eod);
//        $total_transaction = Transaction::where('date', '>=', $bod)
//            ->where('date', '<=', $eod)
//            ->sum('amount');

        return $this->layout->nest('content', 'settlement.edit', array(
            'settlement' => $settlement,
            //'total_transaction' => $total_transaction
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('settlement/index');
        }
        $settlementdata = Input::all();
        $settlementdata['clerk_user_id'] = Auth::user()->id;

        //dd($settlementdata);
        $validation = Validator::make(Input::all(), $this->getRules('edit'));
        if(!$validation->fails()) {
            $success = Settlement::update($id, $settlementdata);
            if($success) {
                //success edit
                Session::flash('message', 'Success update');
                return Redirect::to('settlement/index');
            } else {
                Session::flash('message_error', 'Failed update');
                return Redirect::to_action('settlement@edit', array($id));
            }
        } else {
            return Redirect::to_action('settlement@edit', array($id))
                ->with_errors($validation);
        }
    }
//
//    UNUSED METHOD CUZ SETTLEMENT CAN ONLY SETTLED BASED ON EXISTING BATCH.
//
//    /**
//     * @param date $datesettle use for specify settlement date, format datetime : yyyymmdd
//     * @return mixed
//     */
//    public function get_add($datesettle=null) {
//
//        if($datesettle != null) {
//            $date = date(Settlement::$sql_date_format, strtotime( date('Ymd', strtotime($datesettle)) ));
//        } else {
//            $date = date(Settlement::$sql_date_format);
//        }
//
//        $bod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 00:00:00'));
//        $eod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 23:59:59'));
//        Log::info('bod : ' . $bod);
//        Log::info('eod : ' .$eod);
//
//        $settlement = Settlement::where('settlement_date', '>=', $bod)
//            ->where('settlement_date', '<=', $eod)
//            ->first();
//
//        if(!empty($settlement)) {
//            Session::flash('message', "Settlement for $date is already settled. Show current information instead of create new");
//            return Redirect::to_action('settlement@edit', array($settlement->id));
//        }
//
//        $settlementdata = Session::get('settlement');
//        Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
//        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
//        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
//        Asset::add('settlement.application', 'js/settlement/application.js', array('jquery.timeentry'));
//
//        $total_transaction = Transaction::where('date', '>=', $bod)
//            ->where('date', '<=', $eod)
//            ->sum('amount');
//
//        return $this->layout->nest('content', 'settlement.add', array(
//            'settlement' => $settlementdata,
//            'total_transaction' => $total_transaction,
//            'datesettle' => $datesettle
//        ));
//    }
//
//    public function post_add() {
//        $validation = Validator::make(Input::all(), $this->getRules());
//        $settlementdata = Input::all();
//        $settlementdata['user_id'] = Auth::user()->id;
//        //dd($settlementdata);
//        if(!$validation->fails()) {
//            $success = Settlement::create($settlementdata);
//            if($success) {
//                //success
//                Session::flash('message', 'Success create');
//                return Redirect::to('settlement/index');
//            } else {
//                Session::flash('message_error', 'Failed create');
//                return Redirect::to('settlement/add')
//                    ->with_input();
//            }
//        } else {
//            return Redirect::to('settlement/add')
//                ->with_errors($validation)
//                ->with_input();
//        }
//    }

    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('settlement/index');
        }
        $success = Settlement::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('settlement/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('settlement/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'amount_cash'   =>  'numeric',
            'amount_non_cash'   =>  'numeric',
            'fraction_50'   =>  'numeric',
            'fraction_100'   =>  'numeric',
            'fraction_500'   =>  'numeric',
            'fraction_1000'   =>  'numeric',
            'fraction_2000'   =>  'numeric',
            'fraction_5000'   =>  'numeric',
            'fraction_10000'   =>  'numeric',
            'fraction_20000'   =>  'numeric',
            'fraction_50000'   =>  'numeric',
            'fraction_100000'   =>  'numeric',
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
