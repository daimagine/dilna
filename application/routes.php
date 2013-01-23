<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::any('login', 'user@login');
Route::any('logout', 'user@logout');

Route::get('user/edit/(:num)', 'user@edit');
Route::get('role/edit/(:num)', 'role@edit');
Route::get('access/edit/(:num)', 'access@edit');
Route::get('account/edit/(:num)', 'account@edit');
Route::get('discount/edit/(:num)', 'discount@edit');
Route::get('member/edit/(:num)', 'member@edit');
Route::get('item/edit/(:num)', 'item@edit');
Route::get('asset/edit/(:num)', 'asset@edit');
Route::get('service/edit/(:num)', 'service@edit');
Route::get('account/invoice_edit/(:any)/(:num)', 'account@invoice_edit');
Route::get('customer/edit/(:num)', 'customer@edit');
Route::get('vehicle/edit/(:num)', 'vehicle@edit');
Route::get('work_order/edit/(:num)', 'work_order@edit');
Route::get('news/edit/(:num)', 'news@edit');
Route::get('settlement/edit/(:num)', 'settlement@edit');

Route::controller(array(
    'home', 'user', 'role', 'access', 'account', 'discount', 'member', 'item', 'customer', 'vehicle',
    'service', 'work_order', 'news', 'preferences', 'settlement', 'conversation', 'asset',
    'report.dashboard', 'report.account', 'report.transaction', 'report.warehouse', 'report.finance',
    'sync.local', 'sync.production',
));

Route::get('/', 'home@index');

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

/**
 * Auth check
 *
 * Validates they are logged in.
 */
Route::filter('auth', function()
{
    if ( ! Auth::check() ) {
        return Redirect::to('login')
            ->with('message_error', 'You have to login first');
    }
    if ( ! Auth::has_permissions() && URI::$uri != "/" ) {
        return Redirect::to('home/index')
            ->with('message_error', "<strong>Permission Denied.</strong> You don't have privilege to access page " . URI::$uri);
    }
});