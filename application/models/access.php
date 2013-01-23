<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/13/12
 * Time: 12:56 AM
 * To change this template use File | Settings | File Templates.
 */
class Access extends Eloquent {

    public static $table = 'access';

    public function parentAccess() {
        return $this->belongs_to('Access','parent_id');
    }

    public function childs(){
        return $this->has_many('Access','parent_id');
    }

    public function role_access() {
        return $this->has_many_and_belongs_to('Role', 'role_access')
            ->with('sequence');
    }

    public static function listAll($criteria) {
        return Access::where('status', '=', 1)->get();
    }

    public static function update($id, $data = array()) {
        $access = Access::where_id($id)
            ->where_status(1)
            ->first();
        $access->description = $data['description'];
        $access->status = $data['status'];
        $access->name = $data['name'];
        $access->action = @$data['action'];
        $access->description = @$data['description'];
        $access->parent = isset($data['parent']) ? $data['parent'] : 0;
        $access->visible = isset($data['visible']) ? $data['visible'] : 0;
        $access->type = @$data['type'];
        if(isset($data['parent_id'])) {
            $access->parent_id = $data['parent_id'];
        }
        //save
        $access->save();
        return $access->id;
    }

    public static function create($data = array()) {
        $access = new Access;
        $access->description = $data['description'];
        $access->status = $data['status'];
        $access->name = $data['name'];
        $access->action = @$data['action'];
        $access->description = @$data['description'];
        $access->parent = isset($data['parent']) ? $data['parent'] : 0;
        $access->visible = isset($data['visible']) ? $data['visible'] : 0;
        $access->type = @$data['type'];
        if(isset($data['parent_id'])) {
            $access->parent_id = $data['parent_id'];
        }
        $access->save();
        return $access->id;
    }

    public static function remove($id) {
        $access = Access::find($id);
        $access->status = 0;
        $access->save();
        return $access->id;
    }

    public static function getParents($id = -1) {
        $parent = array(
            0 => 'Select Below'
        );
        $results = Access::where_status(1)
            ->where_parent(1)
            ->where('id', '<>', $id)
            ->get();
        foreach($results as $r) {
            $parent[$r->id] = $r->name;
        }
        return $parent;
    }

    public function parentName() {
        if($this->parentAccess)
            return $this->parentAccess->name;
    }

}
