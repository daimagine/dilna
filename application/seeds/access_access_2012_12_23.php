<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_access_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Access';
        $access->description = 'Access Management';
        $access->action = 'access@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Access Add';
        $child->description = 'Access Management Add';
        $child->action = 'access@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Access Edit';
        $child->description = 'Access Management Edit';
        $child->action = 'access@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Access Delete';
        $child->description = 'Access Management Delete';
        $child->action = 'access@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Access List';
        $child->description = 'Access Management List';
        $child->action = 'access@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

    }

    public function order() {
        return 2;
    }
}