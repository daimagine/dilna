<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 9/22/12
 * Time: 4:47 PM
 * To change this template use File | Settings | File Templates.
 */

class SubAccountTrx extends Eloquent {

    public static $table = 'sub_account_trx';
    private static $accountTrxStatus = null;

    public function account_transaction() {
        return $this->belongs_to('AccountTransaction', 'account_trx_id');
    }

    public static function getByCriteria($criteria) {
//        dd($criteria);
        $ate=SubAccountTrx::join('account_transactions', 'sub_account_trx.account_trx_id', '=', 'account_transactions.id');
        $ate=$ate->join('account', 'account.id', '=', 'sub_account_trx.account_type_id');
        $ate=$ate->join('user', 'user.id', '=', 'account_transactions.create_by');
        $ate=$ate->where('sub_account_trx.approved_status', '=', $criteria['approved_status']);
        if(isset($criteria['id'])) {$ate=$ate->where('sub_account_trx.id', '=', $criteria['id']);}
        if(isset($criteria['account_trx_id']) && $criteria['account_trx_id']!=null  ) {$ate=$ate->where('sub_account_trx.account_trx_id', '=', $criteria['account_trx_id']);}
        if(isset($criteria['account_trx_type'])) {$ate=$ate->where('account_transactions.type', '=', $criteria['account_trx_type']);}
        if(isset($criteria['account_category'])) {$ate=$ate->where('account.category', '=', $criteria['account_category']);}
        $ate=$ate->where('account_transactions.status', '=', $criteria['account_trx_status']);

        return $ate->get('sub_account_trx.*');
    }

    public static function updateStatus($id, $status, $remarks) {
        $subAccountTrx = SubAccountTrx::where_id($id)->first();
        $subAccountTrx->approved_status = $status;
        $subAccountTrx->remarks = $remarks;
        $subAccountTrx->save();
        return $subAccountTrx->id;
    }

}