<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 1/3/13
 * Time: 10:41 AM
 * To change this template use File | Settings | File Templates.
 */
class Sync_Local_Controller extends Base_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
    }

    private static function fetch_last_id() {
        Log::info("ON LOCAL FECTH LAST ID");
        //get table list
        $tables = Config::get('sync.tables');
        $url = Config::get('sync.url.last_id');
        $request = array(
            'tables' => $tables
        );
        //post json
        $response = Utilities\Curl::post_json($url, $request);
        return $response;
    }

    private static function fetch_sync_data() {
        $request = static::fetch_last_id();
        Log::info("ON LOCAL FETCH SYNC DATA : " . print_r($request, true));

        $response = array(
            'tables' => array()
        );
        $data = array();
        if(is_array($request) && array_key_exists('tables', $request)) {
            foreach($request['tables'] as $tbl) {
                //dd($tbl);
                $data['table'] = $tbl['table'];
                $data['systrace'] = $tbl['systrace'] + 1;
                $data['sync_time'] = date('Y-m-d H:i:s');
                $data['last_update'] = $tbl['last_update'];

                $obj = Sync::get_sync_data($tbl['table'], $tbl['last_update']);
                $data['rows'] = sizeof($obj);
                $data['data'] = $obj;

                //push to response
                $response['tables'][] = $data;
            }
        }
        return $response;
    }

    public function get_sync_data() {
        Log::info("ON LOCAL GET SYNC DATA");
        $request = static::fetch_sync_data();
        //dd($request);
        $response = "Sync Failed";
        if(is_array($request) && array_key_exists('tables', $request) && sizeof($request['tables']) > 0) {
            $url = Config::get('sync.url.sync_data');
            //post json
            $response = Utilities\Curl::post_json($url, $request);
        }
        return Response::json($response);
    }

}