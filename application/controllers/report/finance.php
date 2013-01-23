<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 11/7/12
 * Time: 6:57 AM
 * To change this template use File | Settings | File Templates.
 */
class Report_Finance_Controller extends Secure_Controller {


    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'report@dashboard@index');
    }

    public function action_index() {
        return $this->layout->nest('content', 'report.finance.index', array());
    }

    //WO REPORT

    public function action_wo() {
        return $this->layout->nest('content', 'report.finance.wo.index', array());
    }

    public function action_wo_daily() {

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
        $transaction = Transaction::finance_daily($criteria);

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.finance.application', 'js/report/finance/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.finance.wo.daily', array(
            'transactions' => $transaction,
            'wo_status' => @$wo_status,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ));
    }

    public function action_wo_weekly() {
        $this->wo_report_periodic();
    }

    public function action_wo_monthly() {
        $this->wo_report_periodic('MONTH');
    }

    public function wo_report_periodic($period='WEEK') {
//        dd(Input::all());

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
            'date' => array( 'between', $start, $end )
        );
        if(Input::get('wo_status')!==null && Input::get('wo_status')!='') {
            $wo_status = Input::get('wo_status');
            $criteria['wo_status'] = array( '=', $wo_status );
        }

        if($period === 'MONTH') {
            $view = 'report.finance.wo.monthly';
            $transaction = Transaction::finance_monthly($criteria);

        } else {
            $view = 'report.finance.wo.weekly';
            $transaction = Transaction::finance_weekly($criteria);
        }

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.finance.application', 'js/report/finance/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', $view, array(
            'transactions' => $transaction,
            'wo_status' => @$wo_status,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ));
    }



    // SERVICE REPORT

    public function action_service() {
        return $this->layout->nest('content', 'report.finance.service.index', array());
    }

    public function action_service_daily() {

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
        if(Input::get('service_type')!==null && Input::get('service_type')!='') {
            $service_type = Input::get('service_type');
            $criteria['service_type'] = array( '=', $service_type );
        }
        $services = Service::finance_daily($criteria);
        $service_type_opt = Service::allSelect();

        $graphData = $this->service_daily_graph($services);

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.finance.application', 'js/report/finance/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.finance.service.daily', array(
            'services' => $services,
            'service_type' => @$service_type,
            'service_type_opt' => @$service_type_opt,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'graphData' => @$graphData
        ));
    }


    public function action_service_weekly() {
        $this->service_report_periodic();
    }

    public function action_service_monthly() {
        $this->service_report_periodic('MONTH');
    }

    public function service_report_periodic($period='WEEK') {
//        dd(Input::all());

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
            'date' => array( 'between', $start, $end )
        );

        if(Input::get('service_type')!==null && Input::get('service_type')!='') {
            $service_type = Input::get('service_type');
            $criteria['service_type'] = array( '=', $service_type );
        }

        if($period === 'MONTH') {
            $view = 'report.finance.service.monthly';
            $services = Service::finance_monthly($criteria);

        } else {
            $view = 'report.finance.service.weekly';
            $services = Service::finance_weekly($criteria);
        }

        $service_type_opt = Service::allSelect();

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.finance.application', 'js/report/finance/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', $view, array(
            'services' => $services,
            'service_type' => @$service_type,
            'service_type_opt' => @$service_type_opt,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ));
    }




    // PARTS REPORT

    public function action_part() {
        return $this->layout->nest('content', 'report.finance.part.index', array());
    }


    public function action_part_daily() {

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
        if(Input::get('part_type')!==null && Input::get('part_type')!='') {
            $part_type = Input::get('part_type');
            $criteria['part_type'] = array( '=', $part_type );
        }
        if(Input::get('part_category')!==null && Input::get('part_category')!='') {
            $part_category = Input::get('part_category');
            $criteria['part_category'] = array( '=', $part_category );
        }
        if(Input::get('part_unit')!==null && Input::get('part_unit')!='') {
            $part_unit = Input::get('part_unit');
            $criteria['part_unit'] = array( '=', $part_unit );
        }
        $parts = Item::finance_daily($criteria);
        $part_type_opt = ItemType::allSelect();
        $part_category_opt = ItemCategory::allSelect();
        $part_unit_opt = UnitType::allSelect();

        $graphData = $this->part_daily_graph($parts);

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.finance.application', 'js/report/finance/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.finance.part.daily', array(
            'parts' => $parts,
            'part_type' => @$part_type,
            'part_type_opt' => @$part_type_opt,
            'part_category' => @$part_category,
            'part_category_opt' => @$part_category_opt,
            'part_unit' => @$part_unit,
            'part_unit_opt' => @$part_unit_opt,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'graphData' => @$graphData
        ));
    }


    public function action_part_weekly() {
        $this->part_report_periodic();
    }

    public function action_part_monthly() {
        $this->part_report_periodic('MONTH');
    }

    public function part_report_periodic($period='WEEK') {
//        dd(Input::all());

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
            'date' => array( 'between', $start, $end )
        );

        if(Input::get('part_type')!==null && Input::get('part_type')!='') {
            $part_type = Input::get('part_type');
            $criteria['part_type'] = array( '=', $part_type );
        }
        if(Input::get('part_category')!==null && Input::get('part_category')!='') {
            $part_category = Input::get('part_category');
            $criteria['part_category'] = array( '=', $part_category );
        }
        if(Input::get('part_unit')!==null && Input::get('part_unit')!='') {
            $part_unit = Input::get('part_unit');
            $criteria['part_unit'] = array( '=', $part_unit );
        }

        if($period === 'MONTH') {
            $view = 'report.finance.part.monthly';
            $parts = Item::finance_monthly($criteria);

        } else {
            $view = 'report.finance.part.weekly';
            $parts = Item::finance_weekly($criteria);
        }

        $part_type_opt = ItemType::allSelect();
        $part_category_opt = ItemCategory::allSelect();
        $part_unit_opt = UnitType::allSelect();

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.finance.application', 'js/report/finance/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', $view, array(
            'parts' => $parts,
            'part_type' => @$part_type,
            'part_type_opt' => @$part_type_opt,
            'part_category' => @$part_category,
            'part_category_opt' => @$part_category_opt,
            'part_unit' => @$part_unit,
            'part_unit_opt' => @$part_unit_opt,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ));
    }

    private function part_daily_graph($part) {
        $ct = array();
        $data = array();
        foreach($part as $p) {
            if(!in_array($p->part_desc, $ct))
                array_push($ct, $p->part_desc);
        }
        foreach($ct as $c) {
            foreach($part as $p) {
                $idx = date('Y-m-d', strtotime($p->part_date));
                if($c === $p->part_desc) {
                    if(array_key_exists($c, $data)) {
                        if(array_key_exists($idx, $data[$c])) {
                            $data[$c][$idx] = $p->amount + $data[$c][$idx];
                        } else {
                            $data[$c][$idx] = $p->amount;
                        }
                    } else {
                        $data[$c][$idx] = $p->amount;
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
        return $data;
    }

    private function service_daily_graph($part) {
        $ct = array();
        $data = array();
        foreach($part as $p) {
            if(!in_array($p->service_desc, $ct))
                array_push($ct, $p->service_desc);
        }
        foreach($ct as $c) {
            foreach($part as $p) {
                $idx = date('Y-m-d', strtotime($p->service_date));
                if($c === $p->service_desc) {
                    if(array_key_exists($c, $data)) {
                        if(array_key_exists($idx, $data[$c])) {
                            $data[$c][$idx] = $p->amount + $data[$c][$idx];
                        } else {
                            $data[$c][$idx] = $p->amount;
                        }
                    } else {
                        $data[$c][$idx] = $p->amount;
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
        return $data;
    }

}
