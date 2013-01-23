<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/11/12
 * Time: 10:35 PM
 * To change this template use File | Settings | File Templates.
 */
class Role extends Eloquent {

    public static $table = 'role';

    public function users() {
        return $this->has_many('User', 'role_id');
    }

    public function role_access() {
        return $this->has_many_and_belongs_to('Access', 'role_access')
            ->with('sequence');
    }
	
	public function parent() {
		return $this->has_one('Role', 'parent_id');
	}

	public function childs() {
		return $this->has_many('Role', 'parent_id');
	}

    public static function listAll($criteria) {
        return Role::where('status', '=', 1)->get();
    }

    public static function update($id, $data = array()) {
        $role = Role::where_id($id)
            ->where_status(1)
            ->first();
        $role->description = $data['description'];
        $role->status = $data['status'];
        $role->name = $data['name'];
        $role->parent_id = @$data['parent_id'];
        $role->save();
        return $role->id;
    }

    public static function create($data = array()) {
        $role = new Role;
        $role->description = $data['description'];
        $role->status = $data['status'];
        $role->name = $data['name'];
        $role->parent_id = @$data['parent_id'];
        $role->save();
        return $role->id;
    }

    public static function remove($id) {
        $role = Role::find($id);
        $role->status = 0;
        $role->save();
        return $role->id;
    }

    public static function allSelect() {
        $roles = Role::where('status', '=', 1)->get();
        $selection = array();
        foreach($roles as $role) {
            $selection[$role->id] = $role->name;
        }
        return $selection;
    }

    public static function getAvailableAccess($role) {
        $ids = array(-1);
        $selected = $role->role_access()
            ->where_status(1)
            ->get(array('access.id'));
        foreach($selected as $s) {
            array_push($ids, $s->id);
        }
        $access = Access::where_not_in('id', $ids)
            ->order_by('parent_id','asc')
            ->order_by('name','asc')
            ->get();
        return $access;
    }

    public static function getAssignedAccess($role) {
        $access = $role->role_access()
            ->order_by('sequence','asc')
            ->get();
        return $access;
    }

    public static function configureAccess($role, $data) {
        $idx = 0;
        $role->role_access()->delete();
        if(array_key_exists('selectedAccess', $data) && is_array($data)) {
            $access_ids = $data['selectedAccess'];
            foreach($access_ids as $id) {
                $access = Access::find($id);
                $role->role_access()
                    ->attach($access, array('sequence' => $idx));
                $idx++;
            }
            $role->save();
        } else {
            //Session::flash('message_error', 'Please assign at least one access privilege');
            //return Redirect::to_action('role@access', array( $id ));
        }
        return true;
    }

    public static function getAccessRole($user) {
        $role = $user->role;
        $ids = array();
        $selected = $role->role_access()
            ->where_status(1)
            ->get(array('access.id'));
        foreach($selected as $s) {
            array_push($ids, $s->id);
        }
        $model = Config::get('auth.navigation');
        $navigation = DB::table($model)
            ->where(function($query) {
            $query->where('type', '=', 'M')
                ->or_where('type', '=', 'S');
        })
            ->where('status', '=', 1)
            ->where_in('id', $ids)
            ->get();
        return $navigation;
    }

}
