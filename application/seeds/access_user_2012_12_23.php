<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_user_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'User';
        $access->description = 'User Management';
        $access->action = 'user@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'User List';
        $child->description = 'User Management List';
        $child->action = 'user@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'User Add';
        $child->description = 'User Management Add';
        $child->action = 'user@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'User Edit';
        $child->description = 'User Management Edit';
        $child->action = 'user@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'User Delete';
        $child->description = 'User Management Delete';
        $child->action = 'user@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'User Find';
        $child->description = 'User Management Find Ajax Name';
        $child->action = 'user@find';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'User Update Password';
        $child->description = 'User Update Password';
        $child->action = 'user@update_password';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

    }

    public function order() {
        return 12;
    }
}