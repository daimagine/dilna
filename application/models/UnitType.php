<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/7/12
 * Time: 10:10 PM
 * To change this template use File | Settings | File Templates.
 */

class UnitType extends Eloquent{

    public static $table = 'unit_type';
//    public static $timestamps = false;

    public function item_category() {
        return $this->belongs_to('ItemCategory');
    }

    public static function listAll($criteria) {
        $unitType = UnitType::join('item_category', 'item_category.id', '=', 'unit_type.item_category_id');
        if($criteria!=null) {
            if ($criteria['item_category_id']) {$unitType = $unitType->where('unit_type.item_category_id', "=", $criteria['item_category_id']);}
        }
        $unitType = $unitType->get('unit_type.*');
        return $unitType;
    }

    public static function allSelect($criteria=array()) {
        $q = UnitType::where('id', '<>', 0);
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
}