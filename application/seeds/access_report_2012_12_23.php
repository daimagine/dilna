<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 12/23/12
 * Time: 9:25 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_Report_2012_12_23 extends S2\Seed {

    public function grow() {
        $parent = new Access();
        $parent->name = 'Report';
        $parent->description = 'Report Management';
        $parent->action = 'report@dashboard@index';
        $parent->status = true;
        $parent->parent = true;
        $parent->visible = true;
        $parent->type = 'M';
        $parent->save();

        $s = new Access();
        $s->name = 'Report Account';
        $s->description = 'Report Account';
        $s->action = 'report@account@index';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Report Finance';
        $s->description = 'Report Finance';
        $s->action = 'report@finance@index';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Report Warehouse';
        $s->description = 'Report Warehouse';
        $s->action = 'report@warehouse@index';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();

        $s = new Access();
        $s->name = 'Report Transaction';
        $s->description = 'Report Transaction';
        $s->action = 'report@transaction@index';
        $s->status = true;
        $s->parent = false;
        $s->visible = true;
        $s->type = 'S';
        $s->parent_id = $parent->id;
        $s->save();


        //-----------------finance----------------------
        $l = new Access();
        $l->name = 'Report Finance Work Order';
        $l->description = 'Report Finance Work Order';
        $l->action = 'report@finance@wo';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Work Order Daily';
        $l->description = 'Report Finance Work Order Daily';
        $l->action = 'report@finance@wo_daily';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Work Order Weekly';
        $l->description = 'Report Finance Work Order Weekly';
        $l->action = 'report@finance@wo_weekly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Work Order Monthly';
        $l->description = 'Report Finance Work Order Monthly';
        $l->action = 'report@finance@wo_monthly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Service';
        $l->description = 'Report Finance Service';
        $l->action = 'report@finance@service';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Service Daily';
        $l->description = 'Report Finance Service Daily';
        $l->action = 'report@finance@service_daily';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Service Weekly';
        $l->description = 'Report Finance Service Weekly';
        $l->action = 'report@finance@service_weekly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Service Monthly';
        $l->description = 'Report Finance Service Monthly';
        $l->action = 'report@finance@service_monthly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Part';
        $l->description = 'Report Finance Part';
        $l->action = 'report@finance@part';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Part Daily';
        $l->description = 'Report Finance Part Daily';
        $l->action = 'report@finance@part_daily';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Part Weekly';
        $l->description = 'Report Finance Part Weekly';
        $l->action = 'report@finance@part_weekly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Finance Part Monthly';
        $l->description = 'Report Finance Part Monthly';
        $l->action = 'report@finance@part_monthly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();


        //-----------------account----------------------

        $l = new Access();
        $l->name = 'Report Account Daily';
        $l->description = 'Report Account Daily';
        $l->action = 'report@account@daily';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Account Weekly';
        $l->description = 'Report Account Weekly';
        $l->action = 'report@account@weekly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Account Monthly';
        $l->description = 'Report Account Monthly';
        $l->action = 'report@account@monthly';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();
        //-----------------warehouse----------------------
        $l = new Access();
        $l->name = 'Report Warehouse List Item';
        $l->description = 'Report Warehouse List Item';
        $l->action = 'report@warehouse@list_item';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Warehouse List Sales Stock';
        $l->description = 'Report Warehouse List Sales Stock';
        $l->action = 'report@warehouse@list_sales_stock';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Warehouse List Sales Amount';
        $l->description = 'Report Warehouse Sales Amount';
        $l->action = 'report@warehouse@list_sales_amount';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();
        //-----------------transaction----------------------
        $l = new Access();
        $l->name = 'Report Transaction List';
        $l->description = 'Report Transaction List';
        $l->action = 'report@transaction@list';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();

        $l = new Access();
        $l->name = 'Report Transaction Detail';
        $l->description = 'Report Transaction Detail';
        $l->action = 'report@transaction@detail';
        $l->status = true;
        $l->parent = false;
        $l->visible = false;
        $l->type = 'L';
        $l->parent_id = $parent->id;
        $l->save();
    }

    public function order() {
        return 9;
    }


}