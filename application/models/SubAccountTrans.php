<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/8/12
 * Time: 6:35 AM
 * To change this template use File | Settings | File Templates.
 */
class SubAccountTrans extends Eloquent {

    public static $table = 'sub_account_trx';

    public static $sqlformat = 'Y-m-d H:i:s';
    public static $format = 'd-m-Y H:i:s';
    public static $dateformat = 'd-m-Y';
    public static $timeformat = 'H:i:s';


    public function account_transaction() {
        return $this->belongs_to('AccountTransaction');
    }

    public function account() {
        return $this->belongs_to('Account', 'account_type_id');
    }

}
