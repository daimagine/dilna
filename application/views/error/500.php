<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>SpringWizard eBengkel :: {{ (isset($title) ? $title : 'Garage Integrated System') }}</title>
    <!--[if IE]> <link href="<?php echo url('css/ie.css') ?>" rel="stylesheet" type="text/css"> <![endif]-->
    <link href="<?php echo url('css/styles.css') ?>" media="all" type="text/css" rel="stylesheet" />

</head>
<body>

<!-- Top line begins -->
<div id="top">
    <div class="wrapper">
        <a href="<?php echo url('/') ?>" title="" class="logo">
            <img src="<?php echo asset('images/logo.png') ?>" alt=""/>
        </a>

        <!-- Right top nav -->
        <div class="topNav">
            <ul class="userNav">
            </ul>
        </div>

        <!-- Responsive nav -->
        <ul class="altMenu">
        </ul>

        <div class="clear"></div>
    </div>
</div>
<!-- Top line ends -->


<!-- Main content wrapper begins -->
<div class="errorWrapper">
    <span class="errorNum">500</span>
    <div class="errorContent">
        <span class="errorDesc"><span class="icon-warning"></span>Oops! Sorry, an error has occured. Internal Server Error!</span>

        <div class="fluid">
            <a href="<?php echo url('/') ?>" title="" class="buttonM bLightBlue">Back to website</a>

        </div>
    </div>
</div>
<!-- Main content wrapper ends -->
</html>
