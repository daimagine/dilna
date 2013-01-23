<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 12/23/12
 * Time: 9:18 PM
 * To change this template use File | Settings | File Templates.
 */
class Seed_Access_Service_2012_12_23 extends S2\Seed {

    public function grow() {
        $parent = new Access();
        $parent->name = 'Service';
        $parent->description = 'Service Management';
        $parent->action = 'service@index';
        $parent->status = true;
        $parent->parent = true;
        $parent->visible = true;
        $parent->type = 'M';
        $parent->save();

        $s = new Access();
        $s->name = 'Service List';
        $s->description = 'Service List';
        $s->action = 'service@list';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Service Add';
        $s->description = 'Service Add';
        $s->action = 'service@add';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $l = new Access();
        $l->name = 'Service Edit';
        $l->description = 'Service Edit';
        $l->action = 'service@edit';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Service Delete';
        $l->description = 'Service Delete';
        $l->action = 'service@delete';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

    }

    public function order() {
        return 15;
    }
}