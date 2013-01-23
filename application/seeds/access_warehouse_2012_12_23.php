<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 12/23/12
 * Time: 8:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Seed_Access_Warehouse_2012_12_23 extends S2\Seed {

    public function grow() {
        $parent = new Access();
        $parent->name = 'Item';
        $parent->description = 'Item List';
        $parent->action = 'item@index';
        $parent->status = true;
        $parent->parent = true;
        $parent->visible = true;
        $parent->type = 'M';
        $parent->save();

        $s = new Access();
        $s->name = 'Item Approved Invoice';
        $s->description = 'Subnav For WareHouse team approved Open Invoice from Accounting';
        $s->action = 'item@list_approved';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Item Add';
        $s->description = 'Item Add';
        $s->action = 'item@add';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Item List';
        $s->description = 'Item List';
        $s->action = 'item@list';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Item History';
        $s->description = 'Item List History Price And Stock Item';
        $s->action = 'item@list_history';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $l = new Access();
        $l->name = 'Item Approved Process';
        $l->description = 'Item Approved Process';
        $l->action = 'item@approved_action';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Item Add New Approved Item';
        $l->description = 'Put Item For Approved';
        $l->action = 'item@putnewitem';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Item Edit';
        $l->description = 'Item Edit';
        $l->action = 'item@edit';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Item Delete';
        $l->description = 'Item Delete';
        $l->action = 'item@delete';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Item Detail Approved';
        $l->description = 'Item Detail Approved';
        $l->action = 'item@detailApproved';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Item Add List Approved Item';
        $l->description = 'Item Add List Approved Item';
        $l->action = 'item@add_approved_item';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Item Approved Detail';
        $l->description = 'Item Approved Detail';
        $l->action = 'item@detail_approved';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Item List approved';
        $l->description = 'Item List approved';
        $l->action = 'item@lst_item';
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