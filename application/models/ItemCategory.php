<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/16/12
 * Time: 12:01 PM
 * To change this template use File | Settings | File Templates.
 */
class ItemCategory  extends Eloquent
{
    public static $table = 'item_category';
//    public static $timestamps = false;

    public function item() {
        return $this->has_many('Item', 'item_category_id')
            ->where('status', '=', statusType::ACTIVE);
    }



    public static function listAll($criteria) {
        $item_category = ItemCategory::with('item');
        $item_category = $item_category->where('status', '=', 1);
        if($criteria != null) {
            if(isset($criteria['id'])) {
                $item_category = $item_category->where('id', '=', $criteria['category_id']);
            }
        }
        $item_category = $item_category->get();
        return $item_category;
    }

    public static function getCategoryById($category_id) {
        $item_category = ItemCategory::where('status', '=', 1);
        if($category_id!=null) {
            $item_category = $item_category->where('id', '=', $category_id);
        }
        $item_category = $item_category->first();
        return $item_category;
    }

    public static function update($id, $data=array()) {
        $item_category = ItemCategory::where_id($id)
            ->where_status(1)
            -first();
        $item_category->name=$data['name'];
        $item_category->description=$data['description'];
        $item_category->status=$data['status'];
        $item_category->save();
        return $item_category->id;
    }

    public static function create($data=array()) {
        $item_category = new ItemCategory();
        $item_category->name=$data['name'];
        $item_category->description=$data['description'];
        $item_category->status=$data['status'];
        $item_category->save();
        return $item_category->id;
    }

    public static function remove($id) {
        $item_category = ItemCategory::find($id);
        $item_category->status = 0;
        $item_category->save();
        return $item_category->id;
    }

    public static function allSelect($criteria=array()) {
        $q = ItemCategory::where('status', '=', 1);
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
