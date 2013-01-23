<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_preferences_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Preferences';
        $access->description = 'Preferences';
        $access->action = 'preferences@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Preferences List';
        $child->description = 'Preferences List';
        $child->action = 'preferences@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Change Password';
        $child->description = 'Change Password';
        $child->action = 'preferences@change_password';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

    }

    public function order() {
        return 8;
    }
}