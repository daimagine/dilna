<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 12/23/12
 * Time: 8:33 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_Role_2012_12_23 extends S2\Seed {

    public function grow() {
        $parentRole = new Access();
        $parentRole->name = 'Role';
        $parentRole->description = 'Role';
        $parentRole->action = 'role@index';
        $parentRole->status = true;
        $parentRole->parent = true;
        $parentRole->visible = true;
        $parentRole->type = 'M';
        $parentRole->save();

        $rs = new Access();
        $rs->name = 'Role Selection';
        $rs->description = 'Role Access Selection';
        $rs->action = 'role@select';
        $rs->status = true;
        $rs->parent = false;
        $rs->visible = true;
        $rs->type = 'S';
        $rs->parent_id = $parentRole->id;
        $rs->save();


        $rs = new Access();
        $rs->name = 'Role Access';
        $rs->description = 'Role Access Management';
        $rs->action = 'role@access';
        $rs->status = true;
        $rs->parent = false;
        $rs->visible = false;
        $rs->type = 'S';
        $rs->parent_id = $parentRole->id;
        $rs->save();

        $rs = new Access();
        $rs->name = 'Role Add';
        $rs->description = 'Role Add';
        $rs->action = 'role@add';
        $rs->status = true;
        $rs->parent = false;
        $rs->visible = true;
        $rs->type = 'S';
        $rs->parent_id = $parentRole->id;
        $rs->save();

        $rl = new Access();
        $rl->name = 'Role Edit';
        $rl->description = 'Role Edit';
        $rl->action = 'role@edit';
        $rl->status = true;
        $rl->parent = false;
        $rl->visible = false;
        $rl->type = 'L';
        $rl->parent_id = $parentRole->id;
        $rl->save();

        $rl = new Access();
        $rl->name = 'Role Delete';
        $rl->description = 'Role Delete';
        $rl->action = 'role@delete';
        $rl->status = true;
        $rl->parent = false;
        $rl->visible = false;
        $rl->type = 'L';
        $rl->parent_id = $parentRole->id;
        $rl->save();

        $rl = new Access();
        $rl->name = 'Role Detail';
        $rl->description = 'Role Detail';
        $rl->action = 'role@detail';
        $rl->status = true;
        $rl->parent = false;
        $rl->visible = false;
        $rl->type = 'L';
        $rl->parent_id = $parentRole->id;
        $rl->save();
    }

    public function order() {
        return 10;
    }
}