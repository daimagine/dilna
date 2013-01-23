<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/29/12
 * Time: 11:34 PM
 * To change this template use File | Settings | File Templates.
 */

class Service extends Eloquent {

    public static $table = 'service';
//    public static $timestamps = false;

    public function service_formula() {
        return $this->has_many('ServiceFormula', 'service_id')
            ->where('status', '=' , statusType::ACTIVE)->first(); //just got active price
    }


    public static function list_all($criteria) {
        $service = Service::where_in('status', $criteria['status']);
        $service=$service->get();
        return $service;
    }

    public static function create($data=array()) {
        $service = new Service();
        $service->name = $data['name'];
        $service->description = $data['description'];
        $service->status = $data['status'];
        $service->save();
        return $service->id;
    }

    public static function update($id, $data=array()) {
        $service = Service::find($id);
        $service->name = $data['name'];
        $service->description = $data['description'];
        $service->status = $data['status'];
        $service->save();
        return $service->id;
    }


    public static function remove($id) {
        $service =Service::find($id);
        $service->status = statusType::INACTIVE;
        $service->save();
        return $service->id;
    }

//    public static function getCurrentPrice($id) {
//        $service = Service::find($id);
//        $service->service_formiu
//    }



    public static function allSelect($criteria=array()) {
        $q = Service::where('status', '=', 1);
        foreach($criteria as $key => $val) {
            if(is_array($val)) {
                if($val[0] === 'null') {
                    $q->where_null($key);
                } elseif($val[0] === 'not_null') {
                    $q->where_not_null($key);
                } elseif($val[0] === 'within') {
                    $q->where($key, '>=', $val[1]);
                    $q->where($key, '<=', $val[2]);
                } else {
                    $q->where($key, $val[0], $val[1]);
                }
            }
        }
        $accounts = $q->get();
        $selection = array();
        foreach($accounts as $a) {
            $selection[$a->id] = $a->name;
        }
        return $selection;
    }

    public static function criteria_lookup($key, $category = null) {
        if($category == 'finance_daily') {
            $keystore = array(
                'service_type' => 's.id',
                'date' => 't.date',
            );
        } elseif($category == 'finance_weekly') {
            $keystore = array(
                'service_type' => 's.id',
                'date' => 't.date',
            );
        } elseif($category == 'finance_monthly') {
            $keystore = array(
                'service_type' => 's.id',
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
        $q =    "SELECT ".
                    "s.name as service_desc, ".
                    "count(distinct ts.id) as service_count, ".
                    "sum(sf.price) as amount, ".
                    "date(t.date) as service_date ".
                "FROM transaction t ".
                    "INNER JOIN transaction_service AS ts ON ts.transaction_id = t.id ".
                    "INNER JOIN service_formula as sf ON sf.id = ts.service_formula_id ".
                    "INNER JOIN service AS s ON sf.service_id = s.id ";

        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .=   " GROUP BY ".
                    "service_date , ".
                    "service_desc ".
                "ORDER BY ".
                    "service_date desc, service_desc asc";

        $data = DB::query($q, $param);

        $clean = array();
        foreach($data as $d) {
            if($d->service_date !== null && strtoupper($d->service_date) !== 'NULL') {
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
                    "s.name as service_desc, ".
                    "count(distinct ts.id) as service_count, ".
                    "sum(sf.price) as amount ";

        $q .=   "FROM transaction t ".
                    "INNER JOIN transaction_service AS ts ON ts.transaction_id = t.id ".
                    "INNER JOIN service_formula as sf ON sf.id = ts.service_formula_id ".
                    "INNER JOIN service AS s ON sf.service_id = s.id ";

        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .=   " GROUP BY Week(t.date) , service_desc ".
                " ORDER BY ".
                    "Week(t.date) desc, service_desc asc";

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
                    "s.name as service_desc, ".
                    "count(distinct ts.id) as service_count, ".
                    "sum(sf.price) as amount ";

        $q .=   "FROM transaction t ".
                    "INNER JOIN transaction_service AS ts ON ts.transaction_id = t.id ".
                    "INNER JOIN service_formula as sf ON sf.id = ts.service_formula_id ".
                    "INNER JOIN service AS s ON sf.service_id = s.id ";


        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= " GROUP BY year, month, service_desc ORDER BY year desc, month desc, service_desc asc ";

        $data = DB::query($q, $param);

//        dd($data);
//        dd(DB::last_query());
        return $data;
    }

}