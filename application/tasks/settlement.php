<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/21/12
 * Time: 5:56 AM
 * To change this template use File | Settings | File Templates.
 */
class Settlement_Task {

    public function run($arguments) {
        print 'Settlement tasks\n';
    }

    public function daily($args) {

        if(is_array($args) && !empty($args) && $args[0] != null) {
            $date = date(Settlement::$sql_date_format, strtotime( date('Ymd', strtotime($args[0])) ));
        } else {
            $date = date(Settlement::$sql_date_format);
        }

        $bod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 00:00:00'));
        $eod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 23:59:59'));
        Log::info('bod : ' . $bod);
        Log::info('eod : ' .$eod);
        $settlement = Settlement::where('settlement_date', '>=', $bod)
            ->where('settlement_date', '<=', $eod)
            ->first();

        //var_dump($settlement);
        if(empty($settlement)) {
            print date(Settlement::$sql_timestamp_format) . " Settlement for $date is not found. Attempt to create new unsettled settlement\n";

            $settledata = array(
                'settlement_date' => $date
            );
            Settlement::create($settledata);

            print date(Settlement::$sql_timestamp_format) . " Unsettled Settlement created!\n";
        } else {
            print date(Settlement::$sql_timestamp_format) . " Settlement for $date already exist with state $settlement->state_description \n";
        }

    }


    public function reminder($args) {
        if(is_array($args) && !empty($args) && $args[0] != null) {
            $date = date(Settlement::$sql_date_format, strtotime( date('Ymd', strtotime($args[0])) ));
        } else {
            $date = date(Settlement::$sql_date_format);
        }
        $settlements = Settlement::where()
            ->get();
    }

}
