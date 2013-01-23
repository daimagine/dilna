<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fauziah
 * Date: 9/10/12
 * Time: 2:48 AM
 * To change this template use File | Settings | File Templates.
 */
class Secure_Controller extends Controller {

    public $layout = 'layout.base';

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
        $this->filter('before', 'auth');

        Asset::add('style', 'css/styles.css');
        Asset::add('style.table.tools', 'media/css/TableTools.css');
        Asset::add('jquery', 'js/jquery.min.js');
        Asset::add('jquery-ui', 'js/jquery-ui.min.js', array('jquery', 'jquery.ui.mousewheel'));
        Asset::add('jquery-uniform', 'js/plugins/forms/jquery.uniform.js', array('jquery', 'jquery-ui'));

        Asset::add('jquery.dataTables', 'js/plugins/tables/jquery.dataTables.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.sortable', 'js/plugins/tables/jquery.sortable.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.resizable', 'js/plugins/tables/jquery.resizable.js', array('jquery', 'jquery-ui'));

        Asset::add('jquery.zeroclipboard', 'media/js/ZeroClipboard.js', array('jquery.dataTables'));
        Asset::add('jquery.tabletools', 'media/js/TableTools.js', array('jquery.dataTables'));

        Asset::add('jquery.collapsible', 'js/plugins/ui/jquery.collapsible.min.js', array('jquery', 'jquery-ui'));

        Asset::add('jquery.breadcrumbs', 'js/plugins/ui/jquery.breadcrumbs.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.tipsy', 'js/plugins/ui/jquery.tipsy.js', array('jquery', 'jquery-ui'));

        Asset::add('charts.highcharts', 'js/highcharts/highcharts.src.js', array('jquery', 'jquery-ui'));

        Asset::add('bootstrap-js', 'js/bootstrap.js', array('jquery-uniform'));
        Asset::add('application-js', 'js/application.js', array('jquery-uniform'));

        Session::put('active.main.nav', 'home@index');
    }

}