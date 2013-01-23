<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_news_2012_12_23 extends S2\Seed {

    public function grow() {
        $p = new Access();
        $p->name = 'News';
        $p->description = 'News Management';
        $p->action = 'news@index';
        $p->status = true;
        $p->parent = true;
        $p->visible = true;
        $p->type = 'M';
        $p->save();

        $child = new Access();
        $child->parent_id = $p->id;
        $child->name = 'News List';
        $child->description = 'News Management List';
        $child->action = 'news@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $p->id;
        $child->name = 'News Add';
        $child->description = 'News Management Add';
        $child->action = 'news@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $p->id;
        $child->name = 'News Edit';
        $child->description = 'News Management Edit';
        $child->action = 'news@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $p->id;
        $child->name = 'News Delete';
        $child->description = 'News Management Delete';
        $child->action = 'news@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $p->id;
        $child->name = 'News Detail';
        $child->description = 'News Management Detail';
        $child->action = 'news@detail';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $p->id;
        $child->name = 'News All';
        $child->description = 'News Management All';
        $child->action = 'news@all';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

    }

    public function order() {
        return 7;
    }
}