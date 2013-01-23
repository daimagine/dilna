<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adikurniawan
 * Date: 9/15/12
 * Time: 3:38 AM
 *
 */
class Cube extends \Laravel\Auth\Drivers\Eloquent {

    public $navigation = array();

    /**
     * Attempt to log a user into the application.
     *
     * @param  array $arguments
     * @return void
     */
    public function attempt($arguments = array())
    {
        $user = $this->model()->where(function($query) use($arguments)
        {
            $username = Config::get('auth.username');

            $query->where($username, '=', $arguments['username']);

            foreach(array_except($arguments, array('username', 'password', 'remember')) as $column => $val)
            {
                $query->where($column, '=', $val);
            }
        })->first();

        // If the credentials match what is in the database we will just
        // log the user into the application and remember them if asked.
        $password = $arguments['password'];

        $password_field = Config::get('auth.password', 'password');

        if ( ! is_null($user) and Hash::check($password, $user->get_attribute($password_field)))
        {
            Session::put('auth.user.role.access', $this->getNavigation($user));
            return $this->login($user->id, array_get($arguments, 'remember'));
        }

        return false;
    }

    public function navigation() {
        if(Session::has('auth.user.role.access'))
            return Session::get('auth.user.role.access');
        return $this->navigation;
    }

    public function getNavigation($user) {
        $navMenu = array(
            0 => array(
                'title'  => 'Dashboard',
                'action' => 'home@index',
                'image'  => '',
                'childs' => null
            )
        );
        $navigation = Role::getAccessRole($user);
        $subnav = array();
        foreach($navigation as $nav) {
            //if main navigation
            if($nav->type === 'M') {
                $navMenu[$nav->id] = array(
                        'title'  => $nav->name,
                        'action' => $nav->action,
                        'image'  => '',
                        'childs' => null
                    );
            } else {
                $subnav[$nav->id] = $nav;
            }
        }
        foreach($subnav as $nav) {
            $tempMain = $navMenu[$nav->parent_id];
            $tempMain['childs'][$nav->id] = array(
                'title'  => $nav->name,
                'action' => $nav->action,
                'image'  => $nav->image
            );
            $navMenu[$nav->parent_id] = $tempMain;
        }
//        dd($navMenu);
//        foreach($navMenu as $menu) {
//            echo $menu['title'].'<br>';
//            if($menu['childs'] != null) {
//                foreach($menu['childs'] as $child) {
//                    echo '  - '. $child['title'].'<br>';
//                }
//            }
//        }
        return $navMenu;
    }

    public function navModel() {
        $model = Config::get('auth.navigation');
        return new $model;
    }

    public function has_permissions() {
        $temp = URI::$segments;
        $val = false;
        try {
            if(empty($temp)) {
                $uri = '/';
            }
            elseif(is_array($temp) && !empty($temp)) {
//                $uri = $temp[0] . '/' . $temp[1];
                $uri = implode("/", $temp);
            }
            //$val = in_array($uri, Config::get('auth.white_list'));
            foreach(Config::get('auth.white_list') as $wl) {
                //var_dump("uri $uri start with $wl : " . ($wl !== '/' && starts_with($uri, $wl)) . " <br>");
                if($wl !== '/' && starts_with($uri, $wl)) {
                    $val = true;
                    break;
                }
            }
            //dd('');

        } catch (\Laravel\Database\Exception $err) {
            Log::write('error', 'failed to check has permissions. ' . $err);
        }
        if($val)
            return true;
        return $this->model()->check_permission(Auth::user(), $uri);
    }

}
