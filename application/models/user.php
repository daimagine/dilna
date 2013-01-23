<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/10/12
 * Time: 4:08 AM
 * To change this template use File | Settings | File Templates.
 */
class User extends Eloquent {

    public static $table = 'user';
	
    private static $USER_PREFIX = 'AC-';
    private static $USER_LENGTH = 3;

    public function role() {
        return $this->belongs_to('Role');
    }

    public function conversations() {
        return $this->has_many_and_belongs_to('Conversation');
    }

    public function messages() {
        return $this->has_many('Message');
    }
	
    public static function listAll($criteria) {
        $users = User::with(array( 'role' ));
        $users = $users->where_not_in('id', array(0,));
        $users = $users->where_not_in('staff_id', array('AC-000',)); //set here staff_id for superadmin
        $users = $users->get();
        return $users;
//        return User::with(array( 'role' ))
//				->get();
    }

    //====jojo update====//
    public static function listByCiteria($criteria) {
       $user = User::where('status', '=', 1);
       if (isset($criteria['role_id'])) {{$user=$user->where('role_id', '=', $criteria['role_id']);}}
        return $user->get();
    }

    public static function find_name_ajax($logged_user, $name) {
        $name = '%'.$name.'%';
        $users = DB::table('user')
            ->where('id', '<>', $logged_user->id)
            ->where('name', 'LIKE', $name)
            ->get(array('id','name'));
        //dd($users);
        $result = array();
        foreach($users as $u) {
            $result[] = array('id' => $u->id, 'text' => $u->name);
        }
//        dd($result);
        return $result;
    }

    public static function update($id, $data = array()) {
        $user = User::where_id($id)
            ->where_status(1)
            ->first();
        if (isset($data['role_id'])) {
            $role = Role::find($data['role_id']);
            $user->role_id = $role->id;
        }
        if (isset($data['login_id'])) {$user->login_id = $data['login_id'];}
        if (isset($data['status'])) {$user->status = $data['status'];}
        if (isset($data['name'])) {$user->name = $data['name'];}
        if (isset($data['staff_id'])) {$user->staff_id = $data['staff_id'];}
        if (isset($data['address1'])) {$user->address1 = $data['address1'];}
        if (isset($data['address2'])) {$user->address2 = $data['address2'];}
        if (isset($data['city'])) {$user->city = $data['city'];}
        if (isset($data['phone1'])) {$user->phone1 = $data['phone1'];}
        if (isset($data['phone2'])) {$user->phone2 = $data['phone2'];}
        if (isset($data['picture'])) {$user->picture = $data['picture'];}
        if (isset($data['password'])) { $user->password = Hash::make($data['password']);}
        $user->save();
        return $user->id;
    }

    public static function create($data = array()) {
        $user = new User;
        $role = Role::find($data['role_id']);
        $user->role_id = $role->id;
        $user->login_id = $data['login_id'];
        $user->password = Hash::make($data['password']);
        $user->status = $data['status'];
        $user->name = $data['name'];
        $user->staff_id = $data['staff_id'];
        $user->address1 = $data['address1'];
        $user->address2 = $data['address2'];
        $user->city = $data['city'];
        $user->phone1 = $data['phone1'];
        $user->phone2 = $data['phone2'];
        $user->picture = $data['picture'];
        $user->save();
        return $user->id;
    }

    public static function remove($id) {
        $user = User::find($id);
        $user->status = 0;
        $user->save();
        return $user->id;
    }

    public static function check_permission($user, $uri) {
        $uri = str_replace('/', '@', $uri);

        $granted = false;
        $sql = "select count(*) as res from access a inner join role_access ra on ra.access_id = a.id
                inner join role r on ra.role_id = r.id inner join user u on u.role_id = r.id
                where u.id = ? and ? like CONCAT(a.action,'%')";
        $count = DB::query($sql, array($user->id, $uri));

//        dd($count);
//        dd(DB::last_query());
        if(intval($count[0]->res) > 0)
            $granted = true;
        return $granted;
    }
	
    public static function generate_staff_id() {
        $count = DB::table(static::$table)->count();
        $count++;
        //pad static::$USER_LENGTH leading zeros
        $suffix = sprintf('%0' . static::$USER_LENGTH . 'd', $count);
        return static::$USER_PREFIX . $suffix;
    }
	
	public static function unique_login_id($login_id, $id = NULL) {
		$valid = false;
		$query = DB::table(static::$table)
					->where('login_id','=',$login_id);
		if($id != NULL)
			$query->where('id', '<>', $id);
		$valid = $query->count() <= 0 ? true : false;
		//dd($valid);
		return $valid;
	}

    public static function update_password($user, $data) {
        try {
            if(Hash::check($data['password'], $user->password)) {
                $user->password = Hash::make($data['new_password']);
                $user->save();

                return true;
            } else {
                Log::info('Wrong password');
                return false;
            }
        } catch(Exception $ex) {
        }
        return false;
    }

}