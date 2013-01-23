<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/30/12
 * Time: 12:40 PM
 * To change this template use File | Settings | File Templates.
 */
class Report_Account_Controller extends Secure_Controller {

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'report@dashboard@index');
    }

    public function action_index() {
        return $this->layout->nest('content', 'report.account.index', array());
    }

    public function action_daily() {
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
            'paid_date' => array( 'not_null', '' ),
            'invoice_date' => array( 'within', $start, $end )
        );
        if(Input::get('type')!==null) {
            $type = Input::get('type');
            if($type === ACCOUNT_TYPE_DEBIT) {
                $criteria['type'] = array( '=', ACCOUNT_TYPE_DEBIT );
            } elseif($type === ACCOUNT_TYPE_CREDIT) {
                $criteria['type'] = array( '=', ACCOUNT_TYPE_CREDIT );
            }
        }

        $accounts = AccountTransaction::listAll($criteria);

        $graphData = $this->create_daily_graph($accounts);

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.account.application', 'js/report/account/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.account.daily', array(
            'accounts' => $accounts,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'type' => @$type,
            'graphData' => @$graphData
        ));
    }

    public function action_weekly() {
        $this->report_periodic();
    }

    public function action_monthly() {
        $this->report_periodic('MONTH');
    }

    private function report_periodic($period='WEEK') {
        //dd(Input::all());

        $startdate = Input::get('startdate');
        $enddate = Input::get('enddate');
        if($startdate == null && $startdate == '')
            $startdate = date('d-m-Y', strtotime('09/01/2012'));
        if($enddate == null && $enddate == '')
            $enddate = date('d-m-Y');

        $tempdate = DateTime::createFromFormat('d-m-Y H:i:s', $startdate.' 00:00:00');
        $start = $tempdate->format('Y-m-1 H:i:s');
        $tempdate = DateTime::createFromFormat('d-m-Y H:i:s', $enddate.' 23:59:59');
        $end = $tempdate->format('Y-m-t H:i:s');

        $criteria = array(
            'paid_date' => array('not_null'),
            'invoice_date' => array('between', $start, $end),
        );
        if(Input::get('type')!==null) {
            $type = Input::get('type');
            if($type === ACCOUNT_TYPE_DEBIT) {
                $criteria['type'] = array( '=', ACCOUNT_TYPE_DEBIT );
            } elseif($type === ACCOUNT_TYPE_CREDIT) {
                $criteria['type'] = array( '=', ACCOUNT_TYPE_CREDIT );
            }
        }

        if($period === 'MONTH') {
            $view = 'report.account.monthly';
            $accounts = AccountTransaction::monthly($criteria);

        } else {
            $view = 'report.account.weekly';
            $accounts = AccountTransaction::weekly($criteria);
        }

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.account.application', 'js/report/account/application.js', array('jquery.flot', 'jquery.timeentry'));
        return $this->layout->nest('content', $view, array(
            'accounts' => $accounts,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'type' => @$type
        ));
    }

    private function inject_js() {

//        <script type="text/javascript" src="js/plugins/charts/excanvas.min.js"></script>
//        <script type="text/javascript" src="js/plugins/charts/jquery.flot.js"></script>
//        <script type="text/javascript" src="js/plugins/charts/jquery.flot.orderBars.js"></script>
//        <script type="text/javascript" src="js/plugins/charts/jquery.flot.pie.js"></script>
//        <script type="text/javascript" src="js/plugins/charts/jquery.flot.resize.js"></script>
//        <script type="text/javascript" src="js/plugins/charts/jquery.sparkline.min.js"></script>

        Asset::add('report.account.excanvas', 'js/plugins/charts/excanvas.min.js', array('jquery.jquery'));
        Asset::add('report.account.flot', 'js/plugins/charts/jquery.flot.js', array('jquery.excanvas'));
        Asset::add('report.account.flot.orderBars', 'js/plugins/charts/jquery.flot.orderBars.js', array('jquery.flot'));
        Asset::add('report.account.flot.pie', 'js/plugins/charts/jquery.flot.pie.js', array('jquery.flot'));
        Asset::add('report.account.flot.resize', 'js/plugins/charts/jquery.flot.resize.js', array('jquery.flot'));
        Asset::add('report.account.sparkline', 'js/plugins/charts/jquery.sparkline.min.js', array('jquery'));
    }

    private function create_daily_graph($accounts) {
        $ct = array();
        $data = array();
        foreach($accounts as $account) {
            if(!in_array($account->account->name, $ct))
                array_push($ct, $account->account->name);
        }
        foreach($ct as $c) {
            foreach($accounts as $account) {
                $idx = date('Y-m-d', strtotime($account->invoice_date));
                if($c === $account->account->name) {
                    if(array_key_exists($c, $data)) {
                        if(array_key_exists($idx, $data[$c])) {
                            $data[$c][$idx] = $account->paid + $data[$c][$idx];
                        } else {
                            $data[$c][$idx] = $account->paid;
                        }
                    } else {
                        $data[$c][$idx] = $account->paid;
                    }
                } else {
                    if(array_key_exists($c, $data)) {
                        if(array_key_exists($idx, $data[$c])) {
                        } else {
                            $data[$c][$idx] = 0;
                        }
                    } else {
                        $data[$c][$idx] = 0;
                    }
                }
            }
        }

//        {dd($data);}
        return $data;
    }
}
