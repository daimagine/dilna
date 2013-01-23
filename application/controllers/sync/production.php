<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 1/3/13
 * Time: 10:51 AM
 * To change this template use File | Settings | File Templates.
 */
class Sync_Production_Controller extends Base_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
    }

    public function post_fetch_last_id() {
        Log::info("ON POST FETCH LAST ID");
        $data = Input::json();

        Log::info("DATA : " . print_r($data, true));

        $response = array(
            'tables' => array()
        );
        foreach($data->tables as $tblname) {
            $tbl = Sync::get_sync_table($tblname);
            Log::info(print_r($tbl, true));
            if($tbl == null) {
                $tbl = array(
                    'table'         => $tblname,
                    'last_id'       =>  0,
                    'sync_time'     =>  date('Y-m-d H:i:s', strtotime('2012-01-01 00:00:00')),
                    'last_update'   =>  date('Y-m-d H:i:s', strtotime('2012-01-01 00:00:00')),
                    'systrace'      =>  1
                );
                $model = Sync::create($tbl);
                Log::info(print_r($model, true));
            }
            $response['tables'][] = $tbl;
        }

        return Response::json($response);
    }

    public function post_sync_data() {
        Log::info("ON POST SYNC DATA");
        $data = Input::json();

        Log::info("DATA : " . print_r($data, true));

        $response = array(
            'response_code' => '0001',
            'response_msg'  => 'Failed',
            'tables' => array(),
        );
        foreach($data->tables as $table) {
            $tblname = $table->table;
            $systrace = $table->systrace;
            $sync_time = $table->sync_time;
            $rows = $table->rows;
            $data = $table->data;
            //last_id will be update as workaround
            $last_id = 0;
            //last_update will be update as workaround
            //$last_update = date('Y-m-d H:i:s', strtotime('2012-01-01 00:00:00'));
            $last_update = $table->last_update;

            $success = true;
            foreach($data as $row) {
                $success = false;
                $row_data = array();
                $exist = Sync::get_row($tblname, $row->id);
                foreach($row as $field => $value) {
                    if($exist != null && $field === 'id')
                        continue;
                    else
                        $row_data[$field] = $value;
                }
                if($exist != null) {
                    $affected = Sync::update_row($tblname, $row->id, $row_data);
                    if($affected > 0) $success = true;
                    Log::info("UPDATE ROW OF $tblname WITH ID " . $row->id . ". AFFECTED ROW $affected");

                } else {
                    $success = Sync::create_row($tblname, $row_data);
                    Log::info("INSERT NEW ROW OF $tblname WITH ID " . $row->id);
                }
                Log::info("LAST QUERY : " . print_r(DB::last_query(), true));

                if(!$success) {
                    Log::info("PROCESS FAILED. BREAK PROCESS");
                    break;
                }

                //update last_update
                $last_update = $row->updated_at;
                //update last_id
                $last_id = $row->id;

            }

            $tbl = Sync::get_sync_table($tblname);
            Log::info(print_r($tbl, true));
            $update_data = array(
                'table'      => $tblname,
                'last_id'   =>  $last_id,
                'sync_time'    => $sync_time,
                'last_update'    => $last_update,
                'systrace'     =>  $systrace
            );
            if($tbl == null) {
                $tbl = Sync::create($update_data);
            } else {
                Sync::update($tbl->id, $update_data);
                $tbl = Sync::get_sync_table($tblname);
            }

            Log::info(print_r($tbl, true));
            $response['tables'][] = $tbl;

            if($success && $tbl != null && $tbl !== false) {
                $response['response_code'] = '0000';
                $response['response_msg'] = 'Synchronize Success';
            }
        }

        return Response::json($response);
    }
}