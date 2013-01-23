<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_Message_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Messages';
        $access->description = 'Messages';
        $access->action = 'conversation@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Message List';
        $child->description = 'Message List';
        $child->action = 'conversation@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Message New';
        $child->description = 'Message New';
        $child->action = 'conversation@new';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Message Send';
        $child->description = 'Message Send';
        $child->action = 'conversation@send';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Message View';
        $child->description = 'Message View';
        $child->action = 'conversation@view';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

    }

    public function order() {
        return 6;
    }
}