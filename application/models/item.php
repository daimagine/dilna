<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/16/12
 * Time: 8:44 AM
 * To change this template use File | Settings | File Templates.
 */

class Item extends Eloquent {

    public static $table = 'item';
//    public static $timestamps = false;

    private static $REF_PREFIX = 'ITM';
    private static $REF_LENGTH = 10;



    public function item_category() {
        return $this->belongs_to('ItemCategory');
    }

    public function item_unit() {
        return $this->belongs_to('UnitType', 'unit_id');
    }

    public function item_type() {
        return $this->belongs_to('ItemType');
    }

    public function item_stock_flow() {
        return $this->has_many_and_belongs_to('AccountTransactions', 'item_stock_flow')
            ->with('sequence');
    }

    public static function listAll($criteria) {
        $item = Item::where('status', '=', 1);
        if(isset($criteria['item_category_id'])){$item = $item->where('item_category_id', '=', $criteria['item_category_id']);}
        $item=$item->get();
        return $item;

    }

    public static function update($id, $data=array()) {
        $item = Item::where_id($id)
            ->where_status(1)
            ->first();
        if(isset($data['item_type_id'])){
            $item_type= ItemType::find($data['item_type_id']);
            $item->item_type_id=$item_type->id;
        }
        if(isset($data['item_category_id'])){
            $item_category= ItemCategory::find($data['item_category_id']);
            $item->item_category_id = $item_category->id;
        }
        if(isset($data['unit_id'])){
            $unit_type = UnitType::find($data['unit_id']);
            $item->unit_id=$unit_type->id;
        }
        if(isset($data['stock'])){$item->stock=$data['stock'];}
        if(isset($data['stock_minimum'])){$item->stock=$data['stock_minimum'];}
        if(isset($data['name'])){$item->name=$data['name'];}
        if(isset($data['code'])){$item->code=$data['code'];}
        if(isset($data['description'])){$item->description=$data['description'];}
        if(isset($data['price'])){$item->price=$data['price'];}
        if(isset($data['purchase_price'])){$item->purchase_price=$data['purchase_price'];}
        if(isset($data['vendor'])){$item->vendor=$data['vendor'];}
        if(isset($data['status'])){$item->status=$data['status'];}
        $item->save();
        return $item->id;
    }

    public static function create($data=array()) {
        $item = new Item();
        if (isset($data['item_type_id'])) {
            $item_type= ItemType::find($data['item_type_id']);
            $item->item_type_id=$item_type->id;
        }
        $item_category= ItemCategory::find($data['item_category_id']);
        $item->item_category_id = $item_category->id;
        $unit_type = UnitType::find($data['unit_id']);
        $item->unit_id=$unit_type->id;
        $item->name=$data['name'];
        $item->stock_minimum=$data['stock_minimum'];
        $item->code=$data['code'];
        $item->description=$data['description'];
        if(isset($data['stock'])) {
//            $item->stock=$data['stock'];
        }
        if(isset($data['stock_opname'])) {
            $item->stock=$data['stock_opname'];
        }
        $item->price=$data['price'];
        $item->purchase_price=$data['purchase_price'];
        $item->vendor=$data['vendor'];
        $item->status=$data['status'];
//        $item->date=$data['date'];
//        $item->expiry_date=$data['expiry_date'];
        $item->save();
        return $item->id;
    }

    public static function remove($id) {
        $item = Item::find($id);
        $item->status = 0;
        $item->save();
        return $item->id;
    }

    public static function updateStock($id, $stock) {
        $item = Item::where_id($id)
            ->where_status(1)
            ->first();
        $item->stock=$stock;
        $item->save();
        return $item->id;
    }

    public static function code_new_item() {
        $count = DB::table(static::$table)->order_by('id', 'desc')->take(1)->only('id');
        $count++;
        $suffix = sprintf('%0' . static::$REF_LENGTH . 'd', $count);
        return static::$REF_PREFIX . $suffix;
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
        $q = "select ".
            "distinct i.name as name, ".
            "ic.name as category, ".
            "it.name as type, ".
            "ut.name as unit, ".
            "i.code as code, ".
            "i.stock as current_stock, ".
            "(select count(distinct isf.id) from item_stock_flow isf where isf.item_id = i.id) as total_update_stock, ".
            "i.price as sell_price, ".
            "(select count(distinct ip.id) from item_price ip where ip.item_id = i.id) as total_update_sell_price, ".
            "i.price as purchase_price, ".
            "i.vendor as vendor, ".
            "i.status as status, ".
            "i.created_at as create_date, ".
            "i.updated_at as last_update, ".
            "year(trx.date) AS year, ".
            "month(trx.date) AS month, ".
            "(select count(trxit.quantity) from transaction_item trxit inner join transaction trx on trx.id=trxit.transaction_id where trxit.item_id = i.id and trx.status='D') as sales_count ";

        $q .= "from " .
            "item i " .
            "inner join item_category ic on i.item_category_id=ic.id " .
            "inner join item_type it on i.item_type_id=it.id " .
            "inner join unit_type ut on i.unit_id=ut.id " .
            "left join item_stock_flow isf on isf.item_id= i.id ".
            "left join item_price ip on ip.item_id= i.id ".
            "left join transaction_item ti on ti.item_id=it.id ".
            "left join transaction trx on ti.transaction_id=trx.id "
        ;


        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= "ORDER BY name desc "
        ;

        $data = DB::query($q, $param);
        $clean = array();
        foreach($data as $d) {
            if($d->code !== null && strtoupper($d->code) !== 'NULL') {
                array_push($clean, $d);
            }
        }
        return $clean;
    }
    public static function criteria_lookup($key, $category = null) {
        $keystore = array();
        if($category == 'list') {
            $keystore = array(
                'name' => 'i.name',
                'code' => 'i.code',
                'vendor' => 'i.vendor',
                'stock' => 'i.stock',
                'type' => 'it.name',
                'category' => 'ic.id'
            );
        } elseif($category == 'detail') {
            $keystore = array(
                'transaction_id' => 't.id',
                'item_type' => 'i.item_category_id',
                'item_name' => 'i.name',
            );
        } elseif($category == 'finance_daily') {
            $keystore = array(
                'part_type' => 's.item_type_id',
                'part_category' => 's.item_category_id',
                'part_unit' => 's.unit_id',
                'date' => 't.date',
            );
        } elseif($category == 'finance_weekly') {
            $keystore = array(
                'part_type' => 's.item_type_id',
                'part_category' => 's.item_category_id',
                'part_unit' => 's.unit_id',
                'date' => 't.date',
            );
        } elseif($category == 'finance_monthly') {
            $keystore = array(
                'part_type' => 's.item_type_id',
                'part_category' => 's.item_category_id',
                'part_unit' => 's.unit_id',
                'date' => 't.date',
            );
        } elseif($category == 'sales_stock_monthly') {
            $keystore = array(
                'name' => 'i.name',
                'category' => 'ic.id',
                'startYear' => 'year(trx.date)',
                'endYear' => 'year(trx.date)'
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
        $q =    "SELECT ".
                    "s.code as part_code, ".
                    "s.name as part_desc, ".
                    "s.vendor as part_vendor, ".
                    "sc.name as part_category, ".
                    "ut.name as unit_type, ".
                    "count(distinct ts.id) as part_count, ".
                    "sum(sf.price) as amount, ".
                    "date(t.date) as part_date ".
                    "FROM transaction t ".
                    "INNER JOIN transaction_item AS ts ON ts.transaction_id = t.id ".
                    "INNER JOIN item_price as sf ON sf.id = ts.item_price_id ".
                    "INNER JOIN item AS s ON sf.item_id = s.id ".
                    "INNER JOIN item_category AS sc ON sc.id = s.item_category_id ".
                    "INNER JOIN unit_type AS ut ON s.unit_id = ut.id ";

        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .=   " GROUP BY ".
                    "part_date , ".
                    "part_desc ".
                    "ORDER BY ".
                    "part_date desc, part_desc asc";

        $data = DB::query($q, $param);

        $clean = array();
        foreach($data as $d) {
            if($d->part_date !== null && strtoupper($d->part_date) !== 'NULL') {
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

        $q =    "SELECT ".
                    "Date_add(t.date, INTERVAL(1-Dayofweek(t.date)) day) AS week_start, ".
                    "Date_add(t.date, INTERVAL(7-Dayofweek(t.date)) day) AS week_end, ".
                    "s.code as part_code, ".
                    "s.name as part_desc, ".
                    "s.vendor as part_vendor, ".
                    "sc.name as part_category, ".
                    "ut.name as unit_type, ".
                    "count(distinct ts.id) as part_count, ".
                    "sum(sf.price) as amount ";

        $q .=   "FROM transaction t ".
                    "INNER JOIN transaction_item AS ts ON ts.transaction_id = t.id ".
                    "INNER JOIN item_price as sf ON sf.id = ts.item_price_id ".
                    "INNER JOIN item AS s ON sf.item_id = s.id ".
                    "INNER JOIN item_category AS sc ON sc.id = s.item_category_id ".
                    "INNER JOIN unit_type AS ut ON s.unit_id = ut.id ";

        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .=   " GROUP BY ".
                    "Week(t.date) , ".
                    "part_desc ".
                "ORDER BY ".
                    "Week(t.date) desc, part_desc asc";

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

        $q =    "SELECT ".
                    "year(t.date) AS year, ".
                    "month(t.date) AS month, ".
                    "monthname(t.date) AS monthname, ".
                    "s.code as part_code, ".
                    "s.name as part_desc, ".
                    "s.vendor as part_vendor, ".
                    "sc.name as part_category, ".
                    "ut.name as unit_type, ".
                    "count(distinct ts.id) as part_count, ".
                    "sum(sf.price) as amount ";

        $q .=   "FROM transaction t ".
                    "INNER JOIN transaction_item AS ts ON ts.transaction_id = t.id ".
                    "INNER JOIN item_price as sf ON sf.id = ts.item_price_id ".
                    "INNER JOIN item AS s ON sf.item_id = s.id ".
                    "INNER JOIN item_category AS sc ON sc.id = s.item_category_id ".
                    "INNER JOIN unit_type AS ut ON s.unit_id = ut.id ";


        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .=   " GROUP BY ".
                    "year, month, ".
                    "part_desc ".
                "ORDER BY ".
                    "year desc, month desc, part_desc asc";

        $data = DB::query($q, $param);

//        dd($data);
//        dd(DB::last_query());
        return $data;
    }

    public static function list_sales_stock_monthly($criteria=array(), $startYear, $endYear) {
        $param = array();
        $criterion = array();
        foreach($criteria as $key => $val) {
            $key = static::criteria_lookup($key, 'sales_stock_monthly');
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
        $q = "SELECT ".
            "i.name AS name, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='01' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS jan_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='02' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS feb_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='03' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS mar_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='04' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS apr_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='05' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS may_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='06' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS jun_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='07' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS jul_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='08' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS aug_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='09' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS sep_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='10' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS oct_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='11' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS nov_salescount, ".
            "(SELECT SUM(ti.quantity) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id WHERE ti.item_id = i.id AND status='D' AND payment_state='D' AND month(trx.date)='12' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS dec_salescount ";

        $q .= "FROM item i " .
            "INNER JOIN item_category ic ON i.item_category_id=ic.id "
        ;


        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= "ORDER BY name desc "
        ;

        $data = DB::query($q, $param);
        return $data;
    }


    public static function list_sales_amount_monthly($criteria=array(), $startYear, $endYear) {
        $param = array();
        $criterion = array();
        foreach($criteria as $key => $val) {
            $key = static::criteria_lookup($key, 'sales_stock_monthly');
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
        $q = "SELECT ".
            "i.name AS name, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='01' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS jan_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='02' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS feb_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='03' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS mar_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='04' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS apr_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='05' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS may_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='06' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS jun_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='07' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS jul_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='08' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS aug_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='09' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS sep_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='10' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS oct_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='11' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS nov_salesamount, ".
            "(SELECT SUM(ti.quantity * ip.price) FROM transaction_item ti INNER JOIN transaction trx ON trx.id=ti.transaction_id INNER JOIN item_price ip ON ip.id=ti.item_price_id WHERE ti.item_id = i.id AND trx.status='D' AND payment_state='D' AND month(trx.date)='12' AND year(trx.date)>='".$startYear."' AND year(trx.date)<='".$endYear."') AS dec_salesamount ";

        $q .= "FROM item i " .
            "INNER JOIN item_category ic ON i.item_category_id=ic.id "
        ;


        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= "ORDER BY name desc "
        ;

        $data = DB::query($q, $param);
        return $data;
    }

}