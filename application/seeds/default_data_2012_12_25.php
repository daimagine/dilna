<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 12/24/12
 * Time: 11:28 PM
 * To change this template use File | Settings | File Templates.
 */

class Seed_Default_Data_2012_12_25 extends S2\Seed {

    public function grow() {
        $roleAdmin = new Role();
        $roleAdmin->id = 1; //static value-> issue value static at program
        $roleAdmin->name = 'Administrator';
        $roleAdmin->status = true;
        $roleAdmin->description = 'Administrator';
        $roleAdmin->save();

        $role = new Role();
        $role->id = 2; //static value-> issue value static at program
        $role->name = 'Operator';
        $role->status = true;
        $role->description = 'Operator';
        $role->save();

        $role = new Role();
        $role->id = 3; //static value-> issue value static at program
        $role->name = 'Mechanic';
        $role->status = true;
        $role->description = 'Mechanic';
        $role->save();


        $ctgrOil = new ItemCategory();
        $ctgrOil->name = 'Oil';
        $ctgrOil->description = 'Oil Category';
        $ctgrOil->status = true;
        $ctgrOil->picture = 'product-design.png';
        $ctgrOil->save();

        $ctgrParts = new ItemCategory();
        $ctgrParts->name = 'Parts';
        $ctgrParts->description = 'Parts Category';
        $ctgrParts->status = true;
        $ctgrParts->picture = 'config.png';
        $ctgrParts->save();

        $ctgrAcc = new ItemCategory();
        $ctgrAcc->name = 'Accessories';
        $ctgrAcc->description = 'Accessories Category';
        $ctgrAcc->status = true;
        $ctgrAcc->picture = 'basket.png';
        $ctgrAcc->save();

        $table = new UnitType();
        $table->item_category_id = $ctgrOil->id;
        $table->name = 'Other';
        $table->save();

        $table = new UnitType();
        $table->item_category_id = $ctgrParts->id;
        $table->name = 'Other';
        $table->save();

        $table = new UnitType();
        $table->item_category_id = $ctgrAcc->id;
        $table->name = 'Other';
        $table->save();

        DB::table('item_type')->insert(
            array(
                'item_category_id' => $ctgrOil->id,
                'name' => 'Other',
                'description' => 'Other'));
//        $table = new Item();
//        $table->item_category_id = $ctgrOil->id;
//        $table->name = 'Other';
//        $table->description = 'Other';
//        $table->save();

        DB::table('item_type')->insert(
            array(
                'item_category_id' => $ctgrParts->id,
                'name' => 'Other',
                'description' => 'Other'));
//        $table = new ItemType();
//        $table->item_category_id = $ctgrParts->id;
//        $table->name = 'Other';
//        $table->status = true;
//        $table->description = 'Other';
//        $table->save();

        DB::table('item_type')->insert(
            array(
                'item_category_id' => $ctgrAcc->id,
                'name' => 'Other',
                'description' => 'Other'));
//        $table = new ItemType();
//        $table->item_category_id = $ctgrAcc->id;
//        $table->name = 'Other';
//        $table->status = true;
//        $table->description = 'Other';
//        $table->save();


        //------ add role access menu for role administrator -----------
        $access = DB::table('access')->where('action', 'like', 'role%')->order_by('id', 'asc')->get('id');
        $data = array(
            'selectedAccess' => array()
        );
        foreach($access as $a) {
            array_push($data['selectedAccess'], $a->id);
        }
        $role = Role::find($roleAdmin->id);
        $success = Role::configureAccess($role, $data);

        //------- save new user -----------
        $user = new User;
        $user->id = 0;
        $user->role_id = $roleAdmin->id;
        $user->login_id = 'admin';
        $user->password = Hash::make('admin');
        $user->status = true;
        $user->name = 'Administrator';
        $user->staff_id = 'AC-000';
        $user->address1 = 'Jl. T. Rawa Buaya no.9 djakarta';
        $user->address2 = '';
        $user->city = 'Djakarta';
        $user->phone1 = '08561271065';
        $user->phone2 = '087782197234';
        $user->save();

        //-------- save default dummy customer for simple transaction ---------------
        $customer = new Customer;
        $customer->id = 1;
        $customer->name = 'Account Dummy';
        $customer->status = true;
        $customer->address1 = 'djakarta';
        $customer->address2 = 'djakarta';
        $customer->city = 'djakarta';
        $customer->post_code = '12220';
        $customer->phone1 = '6281574372311';
        $customer->phone2 = '6281574372311';
        $customer->additional_info = 'opo wae boleeeh';
        $customer->save();

        //------------save default vehicle ----------------------
        $vehicle = new Vehicle;
        $vehicle->number = 'BOO4YA';
        $vehicle->customer_id = $customer->id;
        $vehicle->status = true;
        $vehicle->type = 'jeep';
        $vehicle->color = 'black';
        $vehicle->model = 'jeep';
        $vehicle->brand = 'jeep';
        $vehicle->description = 'jeep ajiiip dah mobil-nya';
        $vehicle->year = '2000';
        $vehicle->save();
    }


    public function order() {
        return 16;
    }
}