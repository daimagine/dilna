<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/7/13
 * Time: 9:18 PM
 * To change this template use File | Settings | File Templates.
 */


class Seed_User_Superadmin_2013_01_07 extends S2\Seed {

    public function grow() {
        //------ get role administrator ------
        $roleAdmin = Role::find(1)->first();
        //------- save new user -----------
        $user = new User;
        $user->id = 0;
        $user->role_id = $roleAdmin->id;
        $user->login_id = 'admin_ebengkel';
        $user->password = Hash::make('admin');
        $user->status = true;
        $user->name = 'Administrator';
        $user->staff_id = 'AC-000';
        $user->address1 = 'Jl. T. Imum Leung Bata no.9 Penteriek – Banda Aceh';
        $user->address2 = '';
        $user->city = 'Aceh';
        $user->phone1 = '08561271065';
        $user->phone2 = '087782197234';
        $user->save();
    }
}