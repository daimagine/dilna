<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 12/23/12
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Access_account_2012_12_23 extends S2\Seed {

    public function grow() {
        $access = new Access();
        $access->name = 'Account';
        $access->description = 'Account Management';
        $access->action = 'account@index';
        $access->status = true;
        $access->parent = true;
        $access->visible = true;
        $access->type = 'M';
        $access->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account List';
        $child->description = 'Account Management List';
        $child->action = 'account@list';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Add';
        $child->description = 'Account Management Add';
        $child->action = 'account@add';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Edit';
        $child->description = 'Account Management Edit';
        $child->action = 'account@edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Delete';
        $child->description = 'Account Management Delete';
        $child->action = 'account@delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Receivable';
        $child->description = 'Account Receivable';
        $child->action = 'account@account_receivable';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Payable';
        $child->description = 'Account Payable';
        $child->action = 'account@account_payable';
        $child->status = true;
        $child->parent = false;
        $child->visible = true;
        $child->type = 'S';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Pay Invoice';
        $child->description = 'Pay Invoice';
        $child->action = 'account@pay_invoice';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Invoice';
        $child->description = 'Account Invoice';
        $child->action = 'account@invoice_in';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Invoice Edit';
        $child->description = 'Account Invoice Edit';
        $child->action = 'account@invoice_edit';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

        $child = new Access();
        $child->parent_id = $access->id;
        $child->name = 'Account Invoice Delete';
        $child->description = 'Account Invoice Delete';
        $child->action = 'account@invoice_delete';
        $child->status = true;
        $child->parent = false;
        $child->visible = false;
        $child->type = 'L';
        $child->save();

    }

    public function order() {
        return 3;
    }
}