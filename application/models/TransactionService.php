<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 10/8/12
 * Time: 1:22 AM
 * To change this template use File | Settings | File Templates.
 */

class TransactionService extends Eloquent {

    public static $table = 'transaction_service';
//    public static $timestamps = false;

    public function service_formula() {
        return $this->belongs_to('ServiceFormula', 'service_formula_id');
    }
}