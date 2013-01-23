<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/10/12
 * Time: 2:07 AM
 * To change this template use File | Settings | File Templates.
 */
class Base_Controller extends Controller {

    /**
     * Catch-all method for requests that can't be matched.
     *
     * @param  string    $method
     * @param  array     $parameters
     * @return Response
     */
    public function __call($method, $parameters)
    {
        return Response::error('404');
    }

    public function __construct() {
        parent::__construct();
    }

}