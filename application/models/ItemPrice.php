<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/25/12
 * Time: 1:25 AM
 * To change this template use File | Settings | File Templates.
 */

class ItemPrice extends Eloquent {

    public static $table = 'item_price';
//    public static $timestamps = false;
   public static $criteria;

    public function users() {
        return $this->belongs_to('User', 'configured_by');
    }

    public function item() {
        return $this->belongs_to('Item', 'item_id');
    }

    public static function recent() {
        return ItemPrice::where('status', '=', 1)
            ->order_by('created_at', 'asc')
            ->take(5)
            ->get();
    }
    public static  function listAll($criteria) {

        $itemPrice = ItemPrice::where_in('item_price.status', $criteria['status']);
        $itemPrice=$itemPrice->join('item', 'item.id', '=', 'item_price.item_id');
        $itemPrice=$itemPrice->join('item_category', 'item_category.id', '=', 'item.item_category_id');
        $itemPrice=$itemPrice->join('user', 'user.id', '=', 'item_price.configured_by');
        if(isset($criteria['item_id'])) {
            $itemPrice=$itemPrice->where('item_id', '=', $criteria['item_id']);
        }
        if(isset($criteria['item_category_id'])) {
            $itemPrice = $itemPrice->where('item.item_category_id', '=', $criteria['item_category_id']);
        }
        $itemPrice=$itemPrice->get();
//        {{dd($itemPrice);}}
        return $itemPrice;
    }

    public static function getSingleResult($criteria) {
        $itemPrice = ItemPrice::where('status', '=', 1);
        if(isset($criteria['item_id'])) {$itemPrice = $itemPrice->where('item_id', '=', $criteria['item_id']);}
        $itemPrice=$itemPrice->first();
        return $itemPrice;
    }

    public static function create($data=array()) {
        $itemPrice = new ItemPrice();
        $item = Item::find($data['item_id']);
        $itemPrice->purchase_price = $data['purchase_price'];
        $itemPrice->item_id = $item->id;
        $itemPrice->price = $data['purchase_price'];
        $itemPrice->prev_price = $data['prev_price'];
        $itemPrice->status = statusType::ACTIVE;
        $itemPrice->configured_by = Auth::user()->id;
        $itemPrice->save();
        return $itemPrice->id;
    }

    public static function update($id, $criteria) {
        $itemPrice=ItemPrice::where_id($id)
            ->where_status(statusType::ACTIVE)
            ->first();
        if(isset($criteria['status'])){$itemPrice->status = $criteria['status'];}
        $itemPrice->save();
        return $itemPrice->id;
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

//SELECT
//ip.id as priceid,
//it.name as name,
//ip.price as price,
//ip.status as status,
//ip.date as createdate,
//us.name as configuredby
//FROM item_price ip
//INNER JOIN item it ON it.id=ip.item_id
//INNER JOIN item_type ty ON ty.id=it.item_type_id
//INNER JOIN item_category ic ON ic.id=it.item_category_id
//INNER JOIN user us ON us.id=ip.configured_by
//ORDER BY name, createDate desc

        $q = "SELECT ".
            "ip.id as priceid, ".
            "it.name as name, ".
            "ic.name as category, ".
            "ty.name as type, ".
            "ut.name as unit, ".
            "it.code as code, ".
            "ip.price as price, ".
            "ip.status as status, ".
            "ip.date as createdate, ".
            "us.name as configuredby ";

        $q .= "FROM item_price ip " .
            "INNER JOIN item it ON it.id=ip.item_id " .
            "INNER JOIN item_type ty ON ty.id=it.item_type_id  " .
            "INNER JOIN item_category ic ON ic.id=it.item_category_id " .
            "INNER JOIN unit_type ut ON ut.id=it.unit_id " .
            "INNER JOIN user us ON us.id=ip.configured_by "
        ;


        $where = " ";
        if(strlen(trim($where)) > 0)
            $where .= " and ";
        else
            $where .= " where ";
        $where .= implode(" and ", $criterion). " ";

        if(is_array($criterion) && !empty($criterion))
            $q.= $where;

        $q .= "ORDER BY name, createdate desc "
        ;

        $data = DB::query($q, $param);
//        {{dd($data);}}
        $clean = array();
        foreach($data as $d) {
            if($d->priceid !== null && strtoupper($d->priceid) !== 'NULL') {
                array_push($clean, $d);
            }
        }
        return $clean;
    }

    public static function criteria_lookup($key, $category = null) {
        $keystore = array();
        if($category == 'list') {
            $keystore = array(
                'date' => 'ip.date',
                'price' => 'ip.price',
                'name' => 'it.name',
                'code' => 'it.code',
                'vendor' => 'it.vendor',
                'stock' => 'it.stock',
                'type' => 'ty.name',
                'category' => 'ic.id'
            );
        }
        return($keystore[$key]);
    }
}