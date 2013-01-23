<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_settlement_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Settlement';
        $access->description = 'Settlement Management';
        $access->action = 'settlement@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Settlement List';
        $child->description = 'Settlement Management List';
        $child->action = 'settlement@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Settlement Add';
        $child->description = 'Settlement Management Add';
        $child->action = 'settlement@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Settlement Edit';
        $child->description = 'Settlement Management Edit';
        $child->action = 'settlement@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Settlement Delete';
        $child->description = 'Settlement Management Delete';
        $child->action = 'settlement@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

    }

    public function order() {
        return 11;
    }
}