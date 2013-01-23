<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/8/12
 * Time: 1:14 AM
 * To change this template use File | Settings | File Templates.
 */

class Transaction extends Eloquent {

    public static $table = 'transaction';
//    public static $timestamps = false;

    public static $sqlformat = 'Y-m-d H:i:s';
    public static $yyyymmdd_format = 'Ymd';

    public function transaction_service() {
        return $this->has_many('TransactionService');
    }

    public function transaction_item() {
        return $this->has_many('TransactionItem');
    }

    public function user_workorder() {
        return $this->has_many('UserWorkorder');
    }

    public function vehicle() {
        return $this->belongs_to('Vehicle');
    }

    public static function list_all($criteria) {
        $trx = Transaction::with(array('vehicle', 'vehicle.customer'));
        $trx = $trx->where_in('status', $criteria['status']);
        if(isset($criteria['batch_id'])) {$trx = $trx->where('batch_id', '=', $criteria['batch_id']);}
        $trx = $trx->get();
        return $trx;
    }

    public static function get_detail_trx($id) {
        $trx = Transaction::with(array(
            'vehicle',
            'vehicle.customer',
            'vehicle.customer.membership',
            'vehicle.membership',
            'vehicle.membership.discount',
            'transaction_service',
            'transaction_service.service_formula',
            'transaction_service.service_formula.service',
            'transaction_item',
            'transaction_item.item_price.item',
            'transaction_item.item_price.item.item_type',
            'transaction_item.item_price.item.item_category',
            'transaction_item.item_price.item.item_unit',
            'user_workorder',
            'user_workorder.user'));
        $trx = $trx->where('id', '=', $id);
        return $trx->first();
    }

    public static function create($data = array()){
        //prepare save to db
        $trx = new Transaction();
        $trx->customer_name = $data['customerName'];
        $trx->vehicle_id = $data['vehiclesid'];
        $trx->status = statusWorkOrder::OPEN;
        $trx->payment_state = paymentState::INITIATE;
        $trx->date = date(static::$sqlformat);
        $batch = Batch::getSingleResult(array(
            'status' => array(batchStatus::UNSETTLED)
        ));
        if ($batch===null) {
            $batch = Batch::create(array());
        }
        $trx->batch_id = $batch->id;
        $trx->save();


        //define amount variable
        $amountItem=(double)0;
        //add items if available
        if(isset($data['items']) && is_array($data['items'])) {
            foreach($data['items'] as $items) {
                $item_price = ItemPrice::getSingleResult(array('item_id' => $items['item_id']));
                $items['item_price_id']=$item_price->id;
                $trx->transaction_item()->insert($items);
                $itemPrice = (double)Item::find((int)$items['item_id'])->price;
                $amountItem=$amountItem+($itemPrice*$items['quantity']);
                $stock=($item_price->item->stock - $items['quantity']);
                $updatestock = Item::updateStock($item_price->item->id, $stock);
            }
        }

        //add service
        $amountService=(double)0;
        $discAmount=(double)0;
        if(isset($data['services']) && is_array($data['services'])) {
            foreach($data['services'] as $service) {
                $trx->transaction_service()->insert($service);
                $servicePrice = (double)Service::find((int)$service['service_formula_id'])->service_formula()->price;
                $amountService=$amountService+$servicePrice;
            }
            $membership = Member::getSingleResult(array(
                'status' => array(statusType::ACTIVE),
                'vehicle_id' => $data['vehiclesid'],
                'is_member' => true
            ));
            if ($membership) {
                $trx->membership_id=$membership->id;
                if (isset($membership->discount)){
                    $discAmount = $amountService * ($membership->discount->value / 100);
                }
            }
        }

        //add mechanic
        if(isset($data['users']) && is_array($data['users'])) {
            foreach($data['users'] as $user) {
                $trx->user_workorder()->insert($user);
            }
        }


        //GENERATE WORK ORDER & INVOICE NO
        $woNo = 'C'.$data['customerId'].'V'.$data['vehiclesid'].($trx->id);
        $date = date(static::$yyyymmdd_format, time());
        $invNo = $date.($trx->id);
        $trx->invoice_no= $invNo;
        $trx->workorder_no = $woNo;
        $trx->amount = ($amountItem+$amountService);
        $trx->discount_amount = $discAmount;
        $trx->paid_amount = ($amountItem + $amountService - $discAmount);
        $trx->save();
        return $trx->id;
    }


    public static function update($id, $data = array()){

        //prepare save to db
        $trx = Transaction::find($id);
        $trx->vehicle_id = $data['vehiclesid'];

        //add items if available

        $trxItem = TransactionItem::listById(array('id' => $id));
        if ($trxItem) {
//            {{dd($data);}}
            foreach($trxItem as $trx_item) {
                $item_price = ItemPrice::getSingleResult(array('item_id' => $trx_item->item_id));
                $stock=($item_price->item->stock + $trx_item->quantity);
                $updatestock = Item::updateStock($item_price->item->id, $stock);
            }
            //cleanup items
            $affected = DB::table('transaction_item')
                ->where('transaction_id', '=', $id)
                ->delete();
        }
        $amountItem=(double)0;
        if(isset($data['items']) && is_array($data['items'])) {
            foreach($data['items'] as $items) {
                $item_price = ItemPrice::getSingleResult(array('item_id' => $items['item_id']));
                $items['item_price_id']=$item_price->id;
                $trx->transaction_item()->insert($items);
                $itemPrice = (double)Item::find((int)$items['item_id'])->price;
                $amountItem=$amountItem+($itemPrice*$items['quantity']);
                $stock=($item_price->item->stock - $items['quantity']);
                $updatestock = Item::updateStock($item_price->item->id, $stock);
            }
        }

        //add service
        $amountService=(double)0;
        $discAmount=(double)0;
        if(isset($data['services']) && is_array($data['services'])) {
            $affected = DB::table('transaction_service')
                ->where('transaction_id', '=', $id)
                ->delete();

            foreach($data['services'] as $service) {
                $trx->transaction_service()->insert($service);
                $servicePrice = (double)Service::find((int)$service['service_formula_id'])->service_formula()->price;
                $amountService=$amountService+$servicePrice;

                $membership = Member::getSingleResult(array(
                    'status' => array(statusType::ACTIVE),
                    'vehicle_id' => $data['vehiclesid'],
                    'is_member' => true
                ));
                if ($membership) {
                    if (isset($membership->discount)){
                        $discAmount = $amountService * ($membership->discount->value / 100);
                    }
                }
            }
        }

        //add mechanic
        if(isset($data['users']) && is_array($data['users'])) {
            //cleanup mechanic
            $affected = DB::table('user_workorder')
                ->where('transaction_id', '=', $id)
                ->delete();
            foreach($data['users'] as $user) {
                $trx->user_workorder()->insert($user);
            }
        }

        $trx->amount = ($amountItem+$amountService);
        $trx->discount_amount = $discAmount;
        $trx->paid_amount = ($amountItem + $amountService - $discAmount);
        $trx->save();
        return $trx->id;
    }

    public static function update_status($id, $status, $data){
        $trx = Transaction::get_detail_trx($id);
        if (isset($data['complete_date'])) {$trx->complete_date = $data['complete_date'];}
        if (isset($data['payment_date'])) {$trx->payment_date = $data['payment_date'];}
        if (isset($data['payment_method'])) {$trx->payment_method = $data['payment_method'];}
        if (isset($data['payment_state'])) {$trx->payment_state = $data['payment_state'];}
        $trx->status = $status;
        $trx->save();

        if($trx->status === statusWorkOrder::CLOSE && $trx->payment_state === paymentState::DONE) {
            $batch = Batch::find($trx->batch_id);
            $batch->sales_count = ($batch->sales_count) + 1;
            $batch->sales_amount = ($batch->sales_amount) + ($trx->paid_amount);
            $batch->save();

            $batchid = $batch->id;
            if(isset($trx->transaction_service)) {
                foreach($trx->transaction_service as $trx_service) {
                    $batch_service = BatchService::getSingleResult(array(
                        'batch_id' => $batchid,
                        'service_id' => $trx_service->service_formula->service->id
                    ));
                    if($batch_service === null) {
                        $batch_service=BatchService::create(array(
                            'batch_id' => $batchid,
                            'service_id' => $trx_service->service_formula->service->id
                        ));
                    }
                    $batch_service->sales_count=$batch_service->sales_count + 1;
                    $batch_service->sales_amount=($batch_service->sales_amount) + ($trx_service->service_formula->price);
                    $batch_service->save();
                }
            }

            if(isset($trx->transaction_item)) {
                foreach($trx->transaction_item as $trx_item) {
                    $batch_item = BatchItem::getSingleResult(array(
                        'batch_id' => $batchid,
                        'item_id' => $trx_item->item_price->item->id
                    ));
                    if($batch_item === null) {
                        $batch_item=BatchItem::create(array(
                            'batch_id' => $batchid,
                            'item_id' => $trx_item->item_price->item->id
                        ));
                    }
                    $batch_item->sales_count=$batch_item->sales_count + $trx_item->quantity;
                    $batch_item->sales_amount=($batch_item->sales_amount) + ($trx_item->item_price->price * $trx_item->quantity);
                    $batch_item->save();
                }
            }
        }
        return $trx;
    }

    public static function list_report($criteria=array()) {
        $param = array();
        $criterion = array();
        foreach($criteria as $key => $val) {
            $key = static::criteria_lookup($key, 'list');
            if($val[0] === 'not_null') {
                $criterion[] = "$key is not null";

            } elseif($val[0] === 'like') {
                $criterion[] = "LOWER($key) like ?";
                $value = '%'. strtolower($val[1]) . '%';
                array_push($param, $value);

            } elseif($val[0] === 'between') {
                $criterion[] = "$key $val[0] ? and ?";
                array_push($param, $val[1]);
                array_push($param, $val[2]);

            } else {
                $criterion[] = "$key $val[0] ?";
                array_push($param, $val[1]);
            }
        }
        $q = "select " .
                    "distinct t.id as transaction_id, " .
                    "t.invoice_no as invoice_no, " .
                    "t.workorder_no as workorder_no, " .
                    "c.name as customer_name, " .
                    "v.number as vehicle_no, " .
                    "t.status as workorder_status, " .
                    "t.date as transaction_date, " .
                    "(select count(distinct st.id) from transaction_service st where st.transaction_id = t.id) as total_services, ".
                    "(select count(distinct it.id) from transaction_item it ".
                        "inner join item i on i.id = it.item_id where it.transaction_id = t.id and i.item_category_id = 1) as total_parts, " .
                    "t.amount as total_transactions, " .
                    "t.discount_amount as discount, " .
                    "t.payment_method as payment_type ";

        $q .= "from " .
                    "transaction t " .
                    "inner join vehicle v on t.vehicle_id = v.id " .
                    "inner join customer c on v.customer_id = c.id " .
                    "left join transaction_service st on st.transaction_id = t.id "
        ;


        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= "ORDER BY transaction_date desc "
        ;

        $data = DB::query($q, $param);
        $clean = array();
        foreach($data as $d) {
            if($d->invoice_no !== null && strtoupper($d->invoice_no) !== 'NULL') {
                array_push($clean, $d);
            }
        }

//        dd($clean);
//        dd(DB::last_query());
        return $clean;
    }

    public static function detail_report($criteria=array()) {
        $trx = Transaction::with(array(
            'vehicle',
            'vehicle.customer',
            'vehicle.customer.membership',
            'vehicle.membership',
            'vehicle.membership.discount',
            'transaction_service',
            'transaction_service.service_formula',
            'transaction_service.service_formula.service',
            'transaction_item',
            'transaction_item.item_price.item',
            'transaction_item.item_price.item.item_type',
            'transaction_item.item_price.item.item_category',
            'transaction_item.item_price.item.item_unit',
            'user_workorder',
            'user_workorder.user'));

        foreach($criteria as $key => $val) {
            $key = static::criteria_lookup($key, 'detail');
            if($val[0] === 'not_null') {
                $trx = $trx->where_not_null($key);

            } elseif($val[0] === 'like') {
                $value = '%'. strtolower($val[1]) . '%';
                $trx = $trx->where("LOWER($key)", "like", $value);

            } elseif($val[0] === 'between') {
                $trx = $trx->where_between($key, $val[1], $val[2]);

            } else {
                $trx = $trx->where($key, $val[0], $val[1]);
            }
        }
//        dd($criteria);
//        dd($trx);
        $result =  $trx->first();

//        dd($result);
        return $result;
    }

    public static function criteria_lookup($key, $category = null) {
        $keystore = array();
        if($category == 'list') {
            $keystore = array(
                'date' => 't.date',
                'invoice_no' => 't.invoice_no',
                'wo_id' => 't.workorder_no',
                'customer_name' => 'c.name',
                'vehicle_no' => 'v.number',
                'wo_status' => 't.status',
            );
        } elseif($category == 'detail') {
            $keystore = array(
                'transaction_id' => 'transaction.id',
                'item_type' => 'transaction_item.item_price.item.item_category_id',
                'item_name' => 'transaction_item.item_price.item.name',
            );
        } elseif($category == 'finance_daily') {
            $keystore = array(
                'wo_status' => 't.status',
                'date' => 't.date',
            );
        } elseif($category == 'finance_weekly') {
            $keystore = array(
                'wo_status' => 't.status',
                'date' => 't.date',
            );
        } elseif($category == 'finance_monthly') {
            $keystore = array(
                'wo_status' => 't.status',
                'date' => 't.date',
            );
        }
        return($keystore[$key]);
    }

    public static function finance_daily($criteria=array()) {
        $param = array();
        $criterion = array();
        foreach($criteria as $key => $val) {
            $key = static::criteria_lookup($key, 'finance_daily');
            if($val[0] === 'not_null') {
                $criterion[] = "$key is not null";

            } elseif($val[0] === 'like') {
                $criterion[] = "LOWER($key) like ?";
                $value = '%'. strtolower($val[1]) . '%';
                array_push($param, $value);

            } elseif($val[0] === 'between') {
                $criterion[] = "$key $val[0] ? and ?";
                array_push($param, $val[1]);
                array_push($param, $val[2]);

            } else {
                $criterion[] = "$key $val[0] ?";
                array_push($param, $val[1]);
            }
        }
        $q = "SELECT date(t.date) as date, ".
                    "count(distinct t.workorder_no) as total_wo, ".
                    "sum(case when t.status = 'O' then 1 else 0 end) as total_open, ".
                    "sum(case when t.status = 'D' then 1 else 0 end) as total_closed, ".
                    "sum(case when t.status = 'C' then 1 else 0 end) as total_canceled, ".
                    "sum(t.amount) as total_amount ";

        $q .= "FROM transaction t ";

        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= " GROUP BY date(t.date) ORDER BY date(t.date) desc ";

        $data = DB::query($q, $param);

        $clean = array();
        foreach($data as $d) {
            if($d->date !== null && strtoupper($d->date) !== 'NULL') {
                array_push($clean, $d);
            }
        }

//        dd($clean);
//        dd(DB::last_query());
        return $clean;
    }

    public static function finance_weekly($criteria=array()) {
        $param = array();
        $criterion = array();
        foreach($criteria as $key => $val) {
            $key = static::criteria_lookup($key, 'finance_weekly');
            if($val[0] === 'not_null') {
                $criterion[] = "$key is not null";
            } elseif($val[0] === 'between') {
                $criterion[] = "$key $val[0] ? and ?";
                array_push($param, $val[1]);
                array_push($param, $val[2]);
            } else {
                $criterion[] = "$key $val[0] ?";
                array_push($param, $val[1]);
            }
        }

        $q = "SELECT ".
            "Date_add(t.date, INTERVAL(1-Dayofweek(t.date)) day) AS week_start, ".
            "Date_add(t.date, INTERVAL(7-Dayofweek(t.date)) day) AS week_end, ".
            "count(distinct t.workorder_no) as total_wo, ".
            "sum(case when t.status = 'O' then 1 else 0 end) as total_open, ".
            "sum(case when t.status = 'D' then 1 else 0 end) as total_closed, ".
            "sum(case when t.status = 'C' then 1 else 0 end) as total_canceled, ".
            "sum(t.amount) as total_amount ";

        $q .= "FROM transaction t ";

        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= " GROUP BY Week(t.date) ORDER BY Week(t.date) desc ";

        $data = DB::query($q, $param);

//        dd($data);
//        dd(DB::last_query());
        return $data;
    }


    public static function finance_monthly($criteria=array()) {
        $param = array();
        $criterion = array();
        foreach($criteria as $key => $val) {
            $key = static::criteria_lookup($key, 'finance_monthly');
            if($val[0] === 'not_null') {
                $criterion[] = "$key is not null";
            } elseif($val[0] === 'between') {
                $criterion[] = "$key $val[0] ? and ?";
                array_push($param, $val[1]);
                array_push($param, $val[2]);
            } else {
                $criterion[] = "$key $val[0] ?";
                array_push($param, $val[1]);
            }
        }

        $q = "SELECT ".
            "year(t.date) AS year, ".
            "month(t.date) AS month, ".
            "monthname(t.date) AS monthname, ".
            "count(distinct t.workorder_no) as total_wo, ".
            "sum(case when t.status = 'O' then 1 else 0 end) as total_open, ".
            "sum(case when t.status = 'D' then 1 else 0 end) as total_closed, ".
            "sum(case when t.status = 'C' then 1 else 0 end) as total_canceled, ".
            "sum(t.amount) as total_amount ";

        $q .= "FROM transaction t ";

        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= " GROUP BY year, month ORDER BY year desc, month desc ";

        $data = DB::query($q, $param);

//        dd($data);
//        dd(DB::last_query());
        return $data;
    }


}