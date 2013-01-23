<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/5/13
 * Time: 6:48 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_asset_2012_12_23 extends S2\Seed {
    public function grow() {
        $parent = new AssetActiva();
        $parent->name = 'Asset';
        $parent->description = 'Asset index';
        $parent->action = 'asset@index';
        $parent->status = true;
        $parent->parent = true;
        $parent->visible = true;
        $parent->type = 'M';
        $parent->save();

        $s = new AssetActiva();
        $s->name = 'Asset Approved Invoice';
        $s->description = 'Asset Approved Invoice';
        $s->action = 'asset@list_approved';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new AssetActiva();
        $s->name = 'Asset Add';
        $s->description = 'Asset Add';
        $s->action = 'asset@add';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new AssetActiva();
        $s->name = 'Asset List';
        $s->description = 'Asset List';
        $s->action = 'asset@list';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $l = new AssetActiva();
        $l->name = 'Item Approved Process';
        $l->description = 'Item Approved Process';
        $l->action = 'asset@approved_action';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new AssetActiva();
        $l->name = 'Item Add New Approved Item';
        $l->description = 'Put Item For Approved';
        $l->action = 'asset@putnewitem';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new AssetActiva();
        $l->name = 'Item Edit';
        $l->description = 'Item Edit';
        $l->action = 'asset@edit';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new AssetActiva();
        $l->name = 'Item Delete';
        $l->description = 'Item Delete';
        $l->action = 'asset@delete';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new AssetActiva();
        $l->name = 'Item Detail Approved';
        $l->description = 'Item Detail Approved';
        $l->action = 'asset@detailApproved';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new AssetActiva();
        $l->name = 'Item Add List Approved Item';
        $l->description = 'Item Add List Approved Item';
        $l->action = 'asset@add_approved_item';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new AssetActiva();
        $l->name = 'Item Approved Detail';
        $l->description = 'Item Approved Detail';
        $l->action = 'asset@detail_approved';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new AssetActiva();
        $l->name = 'Item List approved';
        $l->description = 'Item List approved';
        $l->action = 'asset@lst_item';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();
    }

    public function order() {
        return 13;
    }
}