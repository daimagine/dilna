<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 11/7/12
 * Time: 1:00 AM
 * To change this template use File | Settings | File Templates.
 */
class Report_Warehouse_Controller extends Secure_Controller {

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'report@dashboard@index');
    }

    public function action_index() {
        return $this->layout->nest('content', 'report.warehouse.index', array());
    }

    public function action_list_item() {
        $name = Input::get('name');
        $code = Input::get('code');
        $vendor = Input::get('vendor');
        $opQryStock = Input::get('opQryStock');
        $stock = Input::get('stock');
        $type = Input::get('type');
        $category = Input::get('category');


        $criteria = array();
        if ($name != null && $name != '') {
            $criteria['name'] =  array( 'like', $name );
        }
        if ($code != null && $code != '') {
            $criteria['code'] =  array( 'like', $code );
        }
        if ($vendor != null && $vendor != '') {
            $criteria['vendor'] =  array( 'like', $vendor );
        }
        if ($stock != null && $stock != '') {
            if ($opQryStock != null && $opQryStock != '') {
                $criteria['stock'] =  array( ($opQryStock != null && $opQryStock != '') ? $opQryStock : '=', $stock );
            }
        }
        if ($type != null && $type != '') {
            $criteria['type'] =  array( 'like', $type );
        }
        if ($category != null && $category != '' &&  $category!=0) {
            $criteria['category'] =  array( '=', $category );
        }

        $items = Item::list_report($criteria);
        $lstCategory = ItemCategory::listAll(array());
        $selectionCategory = array();
        $selectionCategory[0] = '--ALL--';
        foreach($lstCategory as $ctg) {
            $selectionCategory[$ctg->id] = $ctg->name;
        }

        $selectionType = array();
        $selectionType[0] = '--select category first--';

        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.warehouse.application', 'js/report/warehouse/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.warehouse.item', array(
            'items' => $items,
            'name' => $name,
            'code' => $code,
            'vendor' => $vendor,
            'stock' => $stock,
            'opQryStock' => $opQryStock,
            'type' => $type,
            'category' => $category,
            'lstCategory' => $selectionCategory,
            'lstType' => $selectionType,
        ));
    }

    public function action_lst_unit_type($id=null){
        if ($id===null) {
            return Redirect::to('report/warehouse/list_item');
        }
        $lstUnitType = UnitType::listAll(array(
            'item_category_id' => $id,
        ));

        $type=0;
        $selection = array();
        $selection[0] = '--ALL--';
        foreach($lstUnitType as $ctg) {
            $selection[$ctg->id] = $ctg->name;
        }
        return View::make('report.warehouse.selectboxtype', array(
            'lstType' => $selection,
            'type' => $type
        ));
    }

    public function action_history_stock_item() {
        $name = Input::get('name');
        $code = Input::get('code');
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        $stock = Input::get('stock');
        $type = Input::get('type');
        $category = Input::get('category');
        $invoiceNo = Input::get('invoiceNo');
        $refNum = Input::get('refNum');


        if($startDate == null)
            $startDate = date('d-m-Y', strtotime('09/01/2012'));
        if($endDate == null)
            $endDate = date('d-m-Y');
        $tempDate = DateTime::createFromFormat('d-m-Y H:i:s', $startDate.' 00:00:00');
        $start = $tempDate->format('Y-m-d H:i:s');
        $tempDate = DateTime::createFromFormat('d-m-Y H:i:s', $endDate.' 23:59:59');
        $end = $tempDate->format('Y-m-d H:i:s');

        $criteria = array(
            'date' => array( 'between', $start, $end )
        );

        if ($name != null && $name != '') {
            $criteria['name'] =  array( 'like', $name );
        }
        if ($code != null && $code != '') {
            $criteria['code'] =  array( 'like', $code );
        }
        if ($invoiceNo != null && $invoiceNo != '') {
            $criteria['invoiceNo'] =  array( 'like', $invoiceNo );
        }
        if ($refNum != null && $refNum != '') {
            $criteria['refNum'] =  array( 'like', $refNum );
        }
        if ($type != null && $type != '') {
            $criteria['type'] =  array( 'like', $type );
        }
        if ($category != null && $category != '' &&  $category!=0) {
            $criteria['category'] =  array( '=', $category );
        }

        $lstCategory = ItemCategory::listAll(array());
        $selectionCategory = array();
        $selectionCategory[0] = '--ALL--';
        foreach($lstCategory as $ctg) {
            $selectionCategory[$ctg->id] = $ctg->name;
        }

        $listStockHistory = ItemStockFlow::list_report($criteria);
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.warehouse.application', 'js/report/warehouse/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.warehouse.stock', array(
            'name' => $name,
            'code' => $code,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'stock' => $stock,
            'invoiceNo' => $invoiceNo,
            'refNum' => $refNum,
            'type' => $type,
            'category' => $category,
            'lstCategory' => $selectionCategory,
            'listStockHistory' => $listStockHistory,
        ));
    }

    public function action_history_price_item() {
        $name = Input::get('name');
        $code = Input::get('code');
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        $opQryStock = Input::get('opQryStock');
        $type = Input::get('type');
        $category = Input::get('category');
        $price = Input::get('price');

        if($startDate == null)
            $startDate = date('d-m-Y', strtotime('09/01/2012'));
        if($endDate == null)
            $endDate = date('d-m-Y');
        $tempDate = DateTime::createFromFormat('d-m-Y H:i:s', $startDate.' 00:00:00');
        $start = $tempDate->format('Y-m-d H:i:s');
        $tempDate = DateTime::createFromFormat('d-m-Y H:i:s', $endDate.' 23:59:59');
        $end = $tempDate->format('Y-m-d H:i:s');

        $criteria = array(
            'date' => array( 'between', $start, $end )
        );

        if ($name != null && $name != '') {
            $criteria['name'] =  array( 'like', $name );
        }
        if ($code != null && $code != '') {
            $criteria['code'] =  array( 'like', $code );
        }
        if ($price != null && $price != '') {
            $criteria['price'] =  array( ($opQryStock != null && $opQryStock != '') ? $opQryStock : '=', $price );
        }
        if ($type != null && $type != '') {
            $criteria['type'] =  array( 'like', $type );
        }
        if ($category != null && $category != '' &&  $category!=0) {
            $criteria['category'] =  array( '=', $category );
        }

        $lstCategory = ItemCategory::listAll(array());
        $selectionCategory = array();
        $selectionCategory[0] = '--ALL--';
        foreach($lstCategory as $ctg) {
            $selectionCategory[$ctg->id] = $ctg->name;
        }


        $listPriceHistory = ItemPrice::list_report($criteria);
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.warehouse.application', 'js/report/warehouse/application.js', array('jquery.timeentry'));
        return $this->layout->nest('content', 'report.warehouse.price', array(
            'name' => $name,
            'code' => $code,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'price' => $price,
            'opQryStock' => $opQryStock,
            'type' => $type,
            'category' => $category,
            'lstCategory' => $selectionCategory,
            'listPriceHistory' => $listPriceHistory,
        ));
    }

    private function inject_js() {
        Asset::add('highcharts.theme', 'js/highcharts/highcharts.theme.grey.js', array('charts.highcharts'));
        Asset::add('report.account.excanvas', 'js/plugins/charts/excanvas.min.js', array('jquery.jquery'));
        Asset::add('report.account.flot', 'js/plugins/charts/jquery.flot.js', array('jquery.excanvas'));
        Asset::add('report.account.flot.orderBars', 'js/plugins/charts/jquery.flot.orderBars.js', array('jquery.flot'));
        Asset::add('report.account.flot.pie', 'js/plugins/charts/jquery.flot.pie.js', array('jquery.flot'));
        Asset::add('report.account.flot.resize', 'js/plugins/charts/jquery.flot.resize.js', array('jquery.flot'));
        Asset::add('report.account.sparkline', 'js/plugins/charts/jquery.sparkline.min.js', array('jquery'));
    }


    //--------------------- ACTION METHOD FOR SALES STOCK & AMOUNT MONTHLY --------------

    public function action_list_sales_stock() {
        $name = Input::get('name');
        $category = Input::get('category');
        $startYear = Input::get('startYear');
        $endYear = Input::get('endYear');

        $criteria = array();
        if ($name != null && $name != '') {
            $criteria['name'] =  array( 'like', $name );
        }
        if ($category != null && $category != '' &&  $category!=0) {
            $criteria['category'] =  array( '=', $category );
        }
        if ($startYear != null && $startYear != '') {
            //continue
        } else {
            $startYear=  date("Y");
        }
        if ($endYear != null && $endYear != '') {
            //continue
        } else {
            $endYear=  date("Y");
        }

        $items = Item::list_sales_stock_monthly($criteria, $startYear,$endYear);
        $lstCategory = ItemCategory::listAll(array());
        $selectionCategory = array();
        $selectionCategory[0] = '--ALL--';
        foreach($lstCategory as $ctg) {
            $selectionCategory[$ctg->id] = $ctg->name;
        }
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.warehouse.application', 'js/report/warehouse/application.js', array('jquery.timeentry'));

        $dataGraph= $this->create_sales_graph($items, 'stock');
        $this->inject_js();


        return $this->layout->nest('content', 'report.warehouse.sales_stock', array(
            'items' => $items,
            'name' => $name,
            'startYear' => $startYear,
            'endYear' => $endYear,
            'category' => $category,
            'lstCategory' => $selectionCategory,
            'dataGraph' => @$dataGraph
    ));
    }

    public function action_list_sales_amount() {
        $name = Input::get('name');
        $category = Input::get('category');
        $startYear = Input::get('startYear');
        $endYear = Input::get('endYear');

        $criteria = array();
        if ($name != null && $name != '') {
            $criteria['name'] =  array( 'like', $name );
        }
        if ($category != null && $category != '' &&  $category!=0) {
            $criteria['category'] =  array( '=', $category );
        }
        if ($startYear != null && $startYear != '') {
            //continue
        } else {
            $startYear=  date("Y");
        }
        if ($endYear != null && $endYear != '') {
            //continue
        } else {
            $endYear=  date("Y");
        }

        $items = Item::list_sales_amount_monthly($criteria, $startYear,$endYear);
        $lstCategory = ItemCategory::listAll(array());
        $selectionCategory = array();
        $selectionCategory[0] = '--ALL--';
        foreach($lstCategory as $ctg) {
            $selectionCategory[$ctg->id] = $ctg->name;
        }
        Asset::add('jquery.timeentry', 'js/plugins/ui/jquery.timeentry.min.js', array('jquery', 'jquery-ui'));
        Asset::add('report.warehouse.application', 'js/report/warehouse/application.js', array('jquery.timeentry'));

        $dataGraph= $this->create_sales_graph($items, 'amount');
        $this->inject_js();


        return $this->layout->nest('content', 'report.warehouse.sales_amount', array(
            'items' => $items,
            'name' => $name,
            'startYear' => $startYear,
            'endYear' => $endYear,
            'category' => $category,
            'lstCategory' => $selectionCategory,
            'dataGraph' => @$dataGraph
        ));
    }

    private function create_sales_graph($items, $type) {
        if($items === null || count($items) == 0) {
            return null;
        }

        $bulan=array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');//, 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        $test = array(
            'categories' => array(),
            'name' => '',
            'data' => array(
                'y' => array(),
                'color' => array(),
                'drilldown' =>array(
                    'name' => array(),
                    'categories' => array(),
                    'data' => array(),
                    'color' => array(),
                )
            )
        );
        $j=0;
        if($type === 'stock') {
            $test['name'] = 'Sales Count Stock';
        } elseif($type === 'amount') {
            $test['name'] = 'Sales Amount';
        }
        foreach($bulan as $b) {
            $i=0;
            $salesCount=0;
//            $salesAmount=0;
            $y=array();
            $color=array();
            $drilldown_categories=array();
            if($type === 'stock') {
                $drilldown_name='Sales Count Stock Item in';
            } elseif($type === 'amount') {
                $drilldown_name='Sales Amount Item in';
            }
            $drilldown_data=array();
            $drilldown_color=array();
            if ($b === 'Jan') {
                $drilldown_name=$drilldown_name.' January';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->jan_salescount);
                        $salesCount=$salesCount + ($item->jan_salescount);
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->jan_salesamount);
                        $salesCount=$salesCount + ($item->jan_salesamount);
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Feb') {
                $drilldown_name=$drilldown_name.' February';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->feb_salescount);
                        $salesCount=$salesCount+$item->feb_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->feb_salesamount);
                        $salesCount=$salesCount+$item->feb_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
                array_push($y, $salesCount);
            } elseif ($b === 'Mar') {
                $drilldown_name=$drilldown_name.' March';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->mar_salescount);
                        $salesCount=$salesCount+$item->mar_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->mar_salesamount);
                        $salesCount=$salesCount+$item->mar_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Apr') {
                $drilldown_name=$drilldown_name.' April';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->apr_salescount);
                        $salesCount=$salesCount+$item->apr_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->apr_salesamount);
                        $salesCount=$salesCount+$item->apr_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'May') {
                $drilldown_name=$drilldown_name.' May';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->may_salescount);
                        $salesCount=$salesCount+$item->may_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->may_salesamount);
                        $salesCount=$salesCount+$item->may_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Jun') {
                $drilldown_name=$drilldown_name.' June';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->jun_salescount);
                        $salesCount=$salesCount+$item->jun_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->jun_salesamount);
                        $salesCount=$salesCount+$item->jun_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Jul') {
                $drilldown_name=$drilldown_name.' Jule';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->jul_salescount);
                        $salesCount=$salesCount+$item->jul_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->jul_salesamount);
                        $salesCount=$salesCount+$item->jul_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Aug') {
                $drilldown_name=$drilldown_name.' August';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->aug_salescount);
                        $salesCount=$salesCount+$item->aug_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->aug_salesamount);
                        $salesCount=$salesCount+$item->aug_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Sep') {
                $drilldown_name=$drilldown_name.' September';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->sep_salescount);
                        $salesCount=$salesCount+$item->sep_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->sep_salesamount);
                        $salesCount=$salesCount+$item->sep_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Oct') {
                $drilldown_name=$drilldown_name.' October';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->oct_salescount);
                        $salesCount=$salesCount+$item->oct_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->oct_salesamount);
                        $salesCount=$salesCount+$item->oct_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Nov') {
                $drilldown_name=$drilldown_name.' November';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->nov_salescount);
                        $salesCount=$salesCount+$item->nov_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->nov_salesamount);
                        $salesCount=$salesCount+$item->nov_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            } elseif ($b === 'Dec') {
                $drilldown_name=$drilldown_name.' Dec';
                foreach($items as $item) {
                    if($type === 'stock') {
                        array_push($drilldown_data, $item->dec_salescount);
                        $salesCount=$salesCount+$item->dec_salescount;
                    } elseif($type === 'amount') {
                        array_push($drilldown_data, $item->dec_salesamount);
                        $salesCount=$salesCount+$item->dec_salesamount;
                    }
                    array_push($drilldown_categories, $item->name);
                    $drilldown_color = $i;
                    $i++;
                }
            }
            $color= $j;

            array_push($test['categories'], $b);
            array_push($test['data']['color'], $color);
            array_push($test['data']['drilldown']['categories'], $drilldown_categories);
            array_push($test['data']['drilldown']['data'], $drilldown_data);
            array_push($test['data']['drilldown']['color'], $drilldown_color);

            array_push($test['data']['y'], $salesCount);
            array_push($test['data']['drilldown']['name'], $drilldown_name);
            $j=$j+1;
        }
//        {{dd($test);}}
        return $test;
    }
}