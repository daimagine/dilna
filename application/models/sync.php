<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 1/3/13
 * Time: 11:35 AM
 * To change this template use File | Settings | File Templates.
 */
class Sync extends Eloquent {

    public static $table = 'synchronize';
    public static $timestamps = false;

    public static function get_sync_table($tblname) {
        return DB::table(static::$table)
            ->where('table', '=', $tblname)
            ->first();
    }

    public static function get_sync_data($tblname, $last_update) {
        $limit = Config::get('sync.rows');

        return DB::table($tblname)
            ->where('updated_at', '>', $last_update)
            ->order_by('updated_at', 'asc')
            ->take($limit)
            ->get();
    }

    public static function get_row($tblname, $id) {
        return DB::table($tblname)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @static
     * @param $tblname
     * @param $id
     * @param $data
     * @return int affected rows
     */
    public static function update_row($tblname, $id, $data) {
        return DB::table($tblname)
            ->where('id', '=', $id)
            ->update($data);
    }

    /**
     * @static
     * @param $tblname
     * @param $data
     * @return bool success or not
     */
    public static function create_row($tblname, $data) {
        return DB::table($tblname)->insert($data);
    }

}
