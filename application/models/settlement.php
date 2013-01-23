<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/21/12
 * Time: 5:11 AM
 * To change this template use File | Settings | File Templates.
 */
class Settlement extends Eloquent {

    public static $table = 'batch';

    public static $sql_timestamp_format = 'Y-m-d H:i:s';
    public static $sql_date_format = 'Y-m-d';

    public static $format = 'd-m-Y H:i:s';
    public static $dateformat = 'd-m-Y';
    public static $timeformat = 'H:i:s';

    public function clerk() {
        return $this->belongs_to('User', 'clerk_user_id');
    }

    public static function listAll() {
        return Settlement::with('clerk')
            ->order_by('created_at', 'desc')
            ->get();
    }


    public static function update($id, $data = array()) {
        $settlement = Settlement::where_id($id)
            ->first();

        if(array_key_exists('notes', $data))
            $settlement->notes = $data['notes'];

        if(array_key_exists('fraction_50', $data))
            $settlement->fraction_50 = $data['fraction_50'];
        if(array_key_exists('fraction_100', $data))
            $settlement->fraction_100 = $data['fraction_100'];
        if(array_key_exists('fraction_200', $data))
            $settlement->fraction_200 = $data['fraction_200'];
        if(array_key_exists('fraction_500', $data))
            $settlement->fraction_500 = $data['fraction_500'];
        if(array_key_exists('fraction_1000', $data))
            $settlement->fraction_1000 = $data['fraction_1000'];
        if(array_key_exists('fraction_2000', $data))
            $settlement->fraction_2000 = $data['fraction_2000'];
        if(array_key_exists('fraction_5000', $data))
            $settlement->fraction_5000 = $data['fraction_5000'];
        if(array_key_exists('fraction_10000', $data))
            $settlement->fraction_10000 = $data['fraction_10000'];
        if(array_key_exists('fraction_20000', $data))
            $settlement->fraction_20000 = $data['fraction_20000'];
        if(array_key_exists('fraction_50000', $data))
            $settlement->fraction_50000 = $data['fraction_50000'];
        if(array_key_exists('fraction_100000', $data))
            $settlement->fraction_100000 = $data['fraction_100000'];

        if(array_key_exists('clerk_amount_cash', $data))
            $settlement->clerk_amount_cash = $data['clerk_amount_cash'];
        if(array_key_exists('clerk_amount_non_cash', $data))
            $settlement->clerk_amount_non_cash = $data['clerk_amount_non_cash'];

        $settlement->calculate_amount();

        $settlement_date= $data['close_time'];
        $settlement_date = DateTime::createFromFormat(static::$sql_date_format, $settlement_date);
        $settlement->close_time = $settlement_date->format(static::$sql_timestamp_format);;

        if(array_key_exists('state', $data))
            $settlement->state = $data['state'];

        //set as closed batch when saved this record
        $settlement->batch_status = batchStatus::SETTLED;
        //always set after settlement date
        $settlement->is_match($settlement);
        //closed all wo if still open
        Settlement::canceled_wo($settlement->id);

        if(array_key_exists('clerk_user_id', $data)) {
            $settlement->clerk_user_id = $data['clerk_user_id'];
        }
        //dd($settlement);

        //save
        $settlement->save();
        return $settlement->id;
    }

    public static function create($data = array()) {

        $settlement = new Settlement;
        if(array_key_exists('notes', $data))
            $settlement->notes = $data['notes'];

        if(array_key_exists('fraction_50', $data))
            $settlement->fraction_50 = $data['fraction_50'];
        if(array_key_exists('fraction_100', $data))
            $settlement->fraction_100 = $data['fraction_100'];
        if(array_key_exists('fraction_200', $data))
            $settlement->fraction_200 = $data['fraction_200'];
        if(array_key_exists('fraction_500', $data))
            $settlement->fraction_500 = $data['fraction_500'];
        if(array_key_exists('fraction_1000', $data))
            $settlement->fraction_1000 = $data['fraction_1000'];
        if(array_key_exists('fraction_2000', $data))
            $settlement->fraction_2000 = $data['fraction_2000'];
        if(array_key_exists('fraction_5000', $data))
            $settlement->fraction_5000 = $data['fraction_5000'];
        if(array_key_exists('fraction_10000', $data))
            $settlement->fraction_10000 = $data['fraction_10000'];
        if(array_key_exists('fraction_20000', $data))
            $settlement->fraction_20000 = $data['fraction_20000'];
        if(array_key_exists('fraction_50000', $data))
            $settlement->fraction_50000 = $data['fraction_50000'];
        if(array_key_exists('fraction_100000', $data))
            $settlement->fraction_100000 = $data['fraction_100000'];

        if(array_key_exists('clerk_amount_cash', $data))
            $settlement->clerk_amount_cash = $data['clerk_amount_cash'];
        if(array_key_exists('clerk_amount_non_cash', $data))
            $settlement->clerk_amount_non_cash = $data['clerk_amount_non_cash'];

        $settlement->calculate_amount();

        $settlement_date= $data['close_time'];
        $settlement_date = DateTime::createFromFormat(static::$sql_date_format, $settlement_date);
        $settlement->close_time = $settlement_date->format(static::$sql_timestamp_format);;
        $settlement->clerk_time = $settlement->close_time;

        if(array_key_exists('state', $data))
            $settlement->state = $data['state'];

        //always set after settlement date
        $settlement->is_match($settlement);

        if(array_key_exists('clerk_user_id', $data)) {
            $settlement->clerk_user_id = $data['clerk_user_id'];
        }

        $settlement->save();
        return $settlement->id;
    }

    public static function remove($id) {
        $access = Access::find($id);
        $access->status = 0;
        $access->save();
        return $access->id;
    }

    public function is_match() {
        try {

//            $date = date(Settlement::$sql_date_format, strtotime($this->settlement_date));
//            $bod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 00:00:00'));
//            $eod = date(Settlement::$sql_timestamp_format, strtotime($date . ' 23:59:59'));
//            Log::info('bod : ' . $bod);
//            Log::info('eod : ' .$eod);
//            $transactionAmount = Transaction::where('date', '>=', $bod)
//                ->where('date', '<=', $eod)
//                ->sum('amount');
//            Log::info('settlement amount  : ' . $this->clerk_amount);
//            Log::info('amount transaction : ' . $transactionAmount);


            if($this->clerk_user_id > 0) {
                if($this->sales_amount == $this->clerk_amount) {
                    $this->state = SettlementState::SETTLED_MATCH;

                    return true;
                } else {
                    $this->state = SettlementState::SETTLED_UNMATCH;
                }
            }

            //dd($this->state);
        } catch (Exception $err) {
            Log::exception($err);
        }
        return false;
    }

    public function calculate_amount() {
        $this->clerk_amount = $this->clerk_amount_cash + $this->clerk_amount_non_cash;
    }

    public function get_state_description() {
        if($this->state == SettlementState::SETTLED) {
            return "Settled";
        } elseif($this->state == SettlementState::SETTLED_MATCH) {
            return "Settled and Match";
        } elseif($this->state == SettlementState::SETTLED_UNMATCH) {
            return "Settled but Unmatch";
        } elseif($this->state == SettlementState::UNSETTLED) {
            return "Unsettled";
        } else {
            return "Cannot Determine State";
        }
    }

    public static function summaryDashboard() {
        $result = array(
            SettlementState::UNSETTLED => 0,
            SettlementState::SETTLED_UNMATCH => 0,
        );
        $query = "select sum( state = ? ) as unsettled, sum( state = ? ) as unmatch from batch";
        $params = array(SettlementState::UNSETTLED, SettlementState::SETTLED_UNMATCH);
        $obj = DB::query($query, $params);
        $result[SettlementState::UNSETTLED] = $obj[0]->unsettled;
        $result[SettlementState::SETTLED_UNMATCH] = $obj[0]->unmatch;
        //dd($result);
        return $result;
    }


    private static function canceled_wo($batchId) {
        try {
            $woOpen = Transaction::list_all(array(
                'status' => array(statusWorkOrder::OPEN),
                'batch_id' => $batchId
            ));
//            {{dd($woOpen);}}
            if($woOpen != null) {
                foreach ($woOpen as $wo) {
                    $update = Transaction::update_status($wo->id, statusWorkOrder::CANCELED, array(
                        'payment_state' => paymentState::CANCELED
                    ));
                }
            }
        } catch (Exception $err) {
            Log::exception($err);
        }
        return false;
    }
}
