<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/5/13
 * Time: 6:39 PM
 * To change this template use File | Settings | File Templates.
 */

class AssetActiva extends Eloquent {

    public static $table = 'asset';
    private static $REF_PREFIX = 'AST';
    private static $REF_LENGTH = 10;

    public function asset_type() {
        return $this->belongs_to('AssetType', 'type_id');
    }

    public static function listAll($criteria) {
//        $asset = AssetActiva::where('status', '=', statusType::ACTIVE);
        $asset = AssetActiva::where_in('status', $criteria['status']);
        $asset=$asset->get();
        return $asset;

    }

    public static function code_new_asset() {
        $count = DB::table(static::$table)->order_by('id', 'desc')->take(1)->only('id');
        $count++;
        $suffix = sprintf('%0' . static::$REF_LENGTH . 'd', $count);
        return static::$REF_PREFIX . $suffix;
    }

    public static function create($data=array()) {
        $asset = new AssetActiva();
        $asset->name=$data['name'];
        $asset->code=$data['code'];
        $asset_type= AssetType::find($data['asset_type_id']);
        $asset->type_id = $asset_type->id;
        $asset->configured_by = Auth::user()->id;
        $asset->status=statusType::ACTIVE;
        $asset->description=$data['description'];
        $asset->condition=$data['condition'];
        $asset->comments=$data['comments'];
        $asset->location=$data['location'];
        $asset->vendor=$data['vendor'];
        $asset->purchase_price=$data['purchase_price'];
        $asset->save();
        return $asset->id;
    }

    public static function insertMultiData($data=array()) {
        foreach($data['assets'] as $newAsset) {
            $asset = new AssetActiva();
            $asset->name=$data['name'];
            $asset->status=statusType::ACTIVE;
            $asset->code=($newAsset['code']!=null && $newAsset['code']!='' ? $newAsset['code'] : "00000000");
            $asset_type= AssetType::find($data['asset_type_id']);
            $asset->type_id = $asset_type->id;
            $asset->configured_by = Auth::user()->id;
            $asset->description=$data['description'];
            $asset->condition=$newAsset['condition'];
            $asset->comments=$newAsset['comments'];
            $asset->location=$newAsset['location'];
            $asset->vendor=$data['vendor'];
            $asset->purchase_price=$data['purchase_price'];
            $asset->save();
        }
        return $asset->id;
    }


    public static function update($id, $data=array()) {
        $asset = AssetActiva::where_id($id)
            ->first();
        if(isset($data['asset_type_id'])){
            $asset_type= AssetType::find($data['asset_type_id']);
            $asset->type_id = $asset_type->id;
        }
        if(isset($data['name'])){$asset->name=$data['name'];}
        if(isset($data['code'])){$asset->code=$data['code'];}
        if(isset($data['status'])){$asset->status=$data['status'];}
        if(isset($data['location'])){$asset->location=$data['location'];}
        if(isset($data['condition'])){$asset->condition=$data['condition'];}
        if(isset($data['description'])){$asset->description=$data['description'];}
        if(isset($data['comments'])){$asset->comments=$data['comments'];}
        if(isset($data['vendor'])){$asset->vendor=$data['vendor'];}
        if(isset($data['purchase_price'])){$asset->purchase_price=$data['purchase_price'];}
        $asset->save();
        return $asset->id;
    }

    public static function updateStatus($id, $status) {
        $item = AssetActiva::find($id);
        $item->status = $status;
        $item->save();
        return $item->id;
    }
}