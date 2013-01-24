<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>SpringWizard eBengkel :: {{(isset($title) ? $title : 'Garage Integrated System')}}</title>

    <link href="{{ url('css/styles.css') }}" rel="stylesheet" type="text/css" />
    <!--[if IE]> <link href="{{ url('css/ie.css') }}" rel="stylesheet" type="text/css"> <![endif]-->
    {{ Asset::styles() }}

    <!-- javascript library -->
    {{ Asset::scripts() }}
</head>

<body>

<!-- Top line begins -->
<div id="top">
    <div class="wrapper">
        <a href="#" title="" class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="" class="logo-image"/>
        </a>

        <!-- Right top nav -->
        <div class="topNav"></div>
        <div class="clear"></div>
    </div>
</div>
<!-- Top line ends -->

@yield('content')

</body>
</html>
