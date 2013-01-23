<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_Customer_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Customer';
        $access->description = 'Customer Management';
        $access->action = 'customer@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Customer List';
        $child->description = 'Customer Management List';
        $child->action = 'customer@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Customer Add';
        $child->description = 'Customer Management Add';
        $child->action = 'customer@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Customer Edit';
        $child->description = 'Customer Management Edit';
        $child->action = 'customer@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Customer Delete';
        $child->description = 'Customer Management Delete';
        $child->action = 'customer@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $access->save();

    }

    public function order() {
        return 4;
    }
}