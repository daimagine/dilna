<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/30/12
 * Time: 12:04 PM
 * To change this template use File | Settings | File Templates.
 */
class Report_Dashboard_Controller extends Secure_Controller {

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'report@dashboard@index');
    }

    public function action_index() {
        return $this->layout->nest('content', 'report.index', array());
    }

}
