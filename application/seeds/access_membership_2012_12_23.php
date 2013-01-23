<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_membership_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Membership';
        $access->description = 'Membership Management';
        $access->action = 'member@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Membership List';
        $child->description = 'Membership Management List';
        $child->action = 'member@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Membership Add';
        $child->description = 'Membership Management Add';
        $child->action = 'member@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Membership Edit';
        $child->description = 'Membership Management Edit';
        $child->action = 'member@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Membership Delete';
        $child->description = 'Membership Management Delete';
        $child->action = 'member@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Membership Assign';
        $child->description = 'Membership Management Assign';
        $child->action = 'member@assign';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Membership Detail';
        $child->description = 'Membership Management Detail';
        $child->action = 'member@detail';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Discount';
        $child->description = 'Discount List';
        $child->action = 'discount@index';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Discount List';
        $child->description = 'Discount List';
        $child->action = 'discount@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Discount Add';
        $child->description = 'Discount Add';
        $child->action = 'discount@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Discount Edit';
        $child->description = 'Discount Edit';
        $child->action = 'discount@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Discount Delete';
        $child->description = 'Discount Delete';
        $child->action = 'discount@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

    }

    public function order() {
        return 5;
    }
}