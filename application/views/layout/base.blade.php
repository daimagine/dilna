<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>SpringWizard eBengkel :: {{ (isset($title) ? $title : 'Garage Integrated System') }}</title>
    <!--[if IE]> <link href='{{ url("css/ie.css") }}' rel="stylesheet" type="text/css"> <![endif]-->
    {{ Asset::styles() }}

</head>
<body>
    <!-- Top line begins -->
    <div id="top">
        <div class="wrapper">
            <a href="{{ url('/') }}" title="" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="" class="logo-image"/>
            </a>

            <!-- Right top nav -->
            <div class="topNav">
                <ul class="userNav">
                    <li class="tipN" title="Logout">{{ HTML::link('logout', '', array('class' => 'logout')) }}</li>
                    <li class="showTabletP"><a href="#" title="" class="sidebar"></a></li>
                </ul>
                <a title="" class="iButton"></a>
                <a title="" class="iTop"></a>
                <div class="topSearch">
                    <div class="topDropArrow"></div>
                    <!--form action="">
                        <input type="text" placeholder="search..." name="topSearch" />
                        <input type="submit" value="" />
                    </form-->
                </div>
            </div>

            <!-- Responsive nav -->
            <ul class="altMenu">
                @section('navigation')
                    {{ HTML::alt_nav() }}
                @yield_section
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <!-- Top line ends -->


    <!-- Sidebar begins -->
    <div id="sidebar">
        <div class="mainNav">
            <div class="user">
                <a title="" class="leftUserDrop">
                    @if(Auth::user()->picture != null &&  Auth::user()->picture != '')
                        <img src="{{ asset('images/uploads/user/'.Auth::user()->picture) }}" alt="" width="72" height="70"/>
                    @else
                        <img src="{{ asset('images/userLogin.png') }}" alt="" width="72" height="70"/>
                    @endif
                    <span></span>
                </a>
                <span>{{ Auth::user()->name }}</span>
                <ul class="leftUser">
                </ul>
            </div>

            <!-- Responsive nav -->
            <div class="altNav">
                <div class="userSearch">
                    <!-- -->
                </div>

                <!-- User nav -->
                <ul class="userNav">
                    <!-- -->
                </ul>
            </div>

            <!-- Putn Main nav here-->
            <ul class="nav">
                @section('navigation')
                    {{ HTML::main_nav() }}
                @yield_section
            </ul>

        </div>

        <!-- Secondary nav -->
        <div class="secNav">
            <div class="secWrapper">
                <div class="secTop">
                    <div class="balance">
                        <div class="balInfo">Date:<span>{{ date('d F Y H:i:s') }}</span></div>
                    </div>
                    <a href="#" class="triangle-red"></a>
                </div>

                <!-- Tabs container -->
                <div id="tab-container" class="tab-container">
                    <ul class="iconsLine ic3 etabs">
                        <li><a href="{{ url('/') }}" title="Dashboard" class="tipS"><span class="icos-fullscreen"></span></a></li>
                        <li><a href='{{ url("preferences/list") }}' title="Change Password" class="tipS"><span class="icos-user"></span></a></li>
                        <li><a href='{{ url("conversation/list") }}' title="Messages" class="tipS"><span class="icos-archive"></span></a></li>
                    </ul>

                    <!-- Putn Sub nav here-->
                    <div class="divider"><span></span></div>

                    <div id="general" style="display: block; " class="active">
                        @section('subnav')
                            {{ HTML::sub_nav() }}
                        @yield_section
                    </div>

                    <div>
                        @section('sidebar_content')
                        @yield_section
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- Sidebar ends -->


    <!-- Content begins -->
    <div id="content">
        <div class="contentTop">
            <span class="pageTitle"><span class="icon-screen"></span>SpringWizard eBengkel</span>

            <div class="clear"></div>
        </div>

        <!-- Breadcrumbs line -->
        <div class="breadLine">
            <div class="bc">
<!--                <ul id="breadcrumbs" class="breadcrumbs">-->
<!--                    <li><a href="#">Dashboard</a></li>-->
<!--                    <li><a href="#">Home</a> </li>-->
<!--                </ul>-->
            </div>

            <div class="breadLinks">
                <ul>
                    @section('breadLinks')
                    @yield_section
                </ul>
                <div class="clear"></div>
            </div>

        </div>

        <!-- Main content -->
        <div class="wrapper">
            @yield('content')
        </div>
        <!-- Main content ends -->

    </div>
    <!-- Content ends -->
	
	<!-- Modal confirmation -->
	<div id="dialog-confirm" title="Confirmation" style="display: none">
		<p>
			<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
			<span id="dialog-confirm-content">Your action cannot be undone. Are you sure?</span>
		</p>
	</div>
	<!-- Modal confirmation ends -->

    <!-- javascript library -->
    {{ Asset::scripts() }}

    <!-- additional javascript -->
    @section('additional_js')
    @yield_section
</body>