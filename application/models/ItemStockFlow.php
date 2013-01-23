<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jauhaf
 * Date: 9/22/12
 * Time: 8:15 AM
 * To change this template use File | Settings | File Templates.
 */

class ItemStockFlow extends Eloquent {

    public static $table = 'item_stock_flow';
//    public static $timestamps = false;

    public function item() {
        return $this->belongs_to('Item', 'item_id');
    }

    public function sub_account_trx() {
        return $this->belongs_to('SubAccountTrx', 'sub_account_trx_id');
    }

    public function user() {
        return $this->belongs_to('User', 'configured_by');
    }

    public function itemName() {
        if($this->item)
            return $this->item->name;
    }


    public static function listAll($criteria) {
        $its=ItemStockFlow::where_in('item_stock_flow.status', $criteria['status']);
        $its=$its->join('item', 'item.id', '=', 'item_stock_flow.item_id');
        $its=$its->join('item_category', 'item_category.id', '=', 'item.item_category_id');
        $its=$its->join('user', 'user.id', '=', 'item_stock_flow.configured_by');
        $its=$its->join('sub_account_trx', 'sub_account_trx.id', '=', 'item_stock_flow.sub_account_trx_id');
        if(isset($criteria['sub_account_trx_id'])) {
            $its=$its->where('item_stock_flow.sub_account_trx_id', '=', $criteria['sub_account_trx_id']);
        }
        if(isset($criteria['item_category_id'])) {
            $its = $its->where('item.item_category_id', '=', $criteria['item_category_id']);
        }
        return $its->get('item_stock_flow.*');
    }

    public static function create($data=array()) {
        $itemStockFlow = new ItemStockFlow();
        $item= Item::find($data['item_id']);
        $subAccountTrx = SubAccountTrx::find($data['sub_account_trx_id']);
        $itemStockFlow->item_id = $item->id;
        $itemStockFlow->sub_account_trx_id = $subAccountTrx->id;
        $itemStockFlow->quantity = $data['quantity'];
        $itemStockFlow->type = 'O';
        $itemStockFlow->status = statusType::ACTIVE;
        $itemStockFlow->configured_by = Auth::user()->id;
        $itemStockFlow->save();
        return $itemStockFlow->id;
    }

    public static function updateStatus($id, $status) {
        $itemStockFlow =  ItemStockFlow::where_id($id)->first();
        $itemStockFlow->status = $status;
        $itemStockFlow->save();
        return $itemStockFlow->id;
    }

    public static function deleteItemStockFlow($id) {
        $affected = DB::table('item_stock_flow')
            ->where('id', '=', $id)
            ->delete();
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

        $q = "SELECT ".
            "isf.id as isfid, ".
            "it.name as name, ".
            "ic.name as category, ".
            "ty.name as type, ".
            "ut.name as unit, ".
            "it.code as code, ".
            "isf.quantity as stock, ".
            "at.invoice_no as invoiceno, ".
            "at.reference_no as refnum, ".
            "isf.date as createdate, ".
            "us.name as configuredby ";

        $q .= "FROM item_stock_flow isf " .
            "INNER JOIN item it ON it.id=isf.item_id " .
            "INNER JOIN item_type ty ON ty.id=it.item_type_id  " .
            "INNER JOIN unit_type ut ON ut.id=it.unit_id " .
            "INNER JOIN item_category ic ON ic.id=it.item_category_id " .
            "INNER JOIN sub_account_trx sat ON sat.id=isf.sub_account_trx_id " .
            "INNER JOIN account_transactions at ON sat.account_trx_id=at.id " .
            "INNER JOIN user us ON us.id=isf.configured_by "
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
            if($d->isfid !== null && strtoupper($d->isfid) !== 'NULL') {
                array_push($clean, $d);
            }
        }
        return $clean;
    }

    public static function criteria_lookup($key, $category = null) {
        $keystore = array();
        if($category == 'list') {
            $keystore = array(
                'date' => 'isf.date',
                'invoiceNo' => 'at.invoice_no',
                'refNum' => 'at.reference_no',
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