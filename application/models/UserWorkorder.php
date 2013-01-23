<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/8/12
 * Time: 1:22 AM
 * To change this template use File | Settings | File Templates.
 */

class UserWorkorder extends Eloquent {

    public static $table = 'user_workorder';
//    public static $timestamps = false;

    public function user() {
        return $this->belongs_to('User', 'user_id');
    }
}