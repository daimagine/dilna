<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 12/23/12
 * Time: 8:53 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_Wo_2012_12_23 extends S2\Seed {

    public function grow() {
        $parent = new Access();
        $parent->name = 'Work Order';
        $parent->description = 'Work Order';
        $parent->action = 'work_order@index';
        $parent->status = true;
        $parent->parent = true;
        $parent->visible = true;
        $parent->type = 'M';
        $parent->save();

        $s = new Access();
        $s->name = 'Work Order Add';
        $s->description = 'Work Order Add';
        $s->action = 'work_order@add';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Work Order List';
        $s->description = 'Work Order List';
        $s->action = 'work_order@list';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $l = new Access();
        $l->name = 'Work Order to invoice';
        $l->description = 'Work Order to invoice';
        $l->action = 'work_order@to_invoice';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order Reopen';
        $l->description = 'Work Order Reopen';
        $l->action = 'work_order@reopen';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order Edit';
        $l->description = 'Work Order Edit';
        $l->action = 'work_order@edit';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order Detail';
        $l->description = 'Work Order Detail';
        $l->action = 'work_order@detail';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order Closed';
        $l->description = 'Work Order Closed';
        $l->action = 'work_order@do_closed';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order Canceled';
        $l->description = 'Work Order Canceled';
        $l->action = 'work_order@do_canceled';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order list items';
        $l->description = 'Work Order list items';
        $l->action = 'work_order@lst_items';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order List Customer';
        $l->description = 'Work Order List Customer';
        $l->action = 'work_order@lst_customer';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Work Order Print Invoice';
        $l->description = 'Work Order Print Invoice';
        $l->action = 'work_order@print_invoice';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();
    }

    public function order() {
        return 14;
    }
}