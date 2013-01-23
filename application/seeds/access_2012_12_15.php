<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/15/12
 * Time: 10:22 AM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_2012_12_15 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Home';
        $access->description = 'Home Page';
        $access->action = 'home/index';
        $access->status = true;
        $access->parent = false;
        $access->visible = true;
        $access->type = 'M';
        $access->save();
    }

    public function order() {
        return 1;
    }
}