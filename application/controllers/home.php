<?php

class Home_Controller extends Secure_Controller {

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/


    public function __construct() {
        parent::__construct();
    }

    public function action_index() {
        $members = Member::recent();
        $news = News::recent();
        $item_prices = ItemPrice::recent();
        $settlements = Settlement::summaryDashboard();

        $payables = AccountTransaction::dueRemaining(ACCOUNT_TYPE_CREDIT);
        $totalPayables = AccountTransaction::dueRemainingTotal(ACCOUNT_TYPE_CREDIT);
        $payablesExp = AccountTransaction::expired(ACCOUNT_TYPE_CREDIT);
        $totalPayablesExp = AccountTransaction::expiredTotal(ACCOUNT_TYPE_CREDIT);

        $receivables = AccountTransaction::dueRemaining(ACCOUNT_TYPE_DEBIT);
        $totalReceivables = AccountTransaction::dueRemainingTotal(ACCOUNT_TYPE_DEBIT);
        $receivablesExp = AccountTransaction::expired(ACCOUNT_TYPE_DEBIT);
        $totalReceivablesExp = AccountTransaction::expiredTotal(ACCOUNT_TYPE_DEBIT);

        Asset::add('home.application', 'js/home/application.js', array('jquery'));
        return $this->layout->nest('content', 'home.dashboard', array(
            'news' => $news,
            'members' => $members,
            'settlements' => $settlements,
            'item_prices' => $item_prices,

            'payables' => $payables,
            'totalPayables' => $totalPayables,
            'payablesExp' => $payablesExp,
            'totalPayablesExp' => $totalPayablesExp,

            'receivables' => $receivables,
            'totalReceivables' => $totalReceivables,
            'receivablesExp' => $receivablesExp,
            'totalReceivablesExp' => $totalReceivablesExp,
        ));
	}

    public function action_sandbox() {
        return Response::error(500);
        Asset::add('home.application', 'js/home/sandbox.js', array('jquery', 'charts.highcharts'));
        return $this->layout->nest('content', 'home.sandbox', array() );
    }

}