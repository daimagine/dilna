<?php

/**
 * Create a HTML input element.
 *
 * @param  string  $type
 * @param  string  $name
 * @param  mixed   $value
 * @param  mixed   $valueLabel
 * @param  array   $attributesInput
 * @param  array   $attributesLabel
 * @return string
 */
Form::macro('nginput', function($type, $name, $value = null, $valueLabel = null, $attributesInput = array(), $attributesLabel = array())
{
    $html = '<div class="formRow">';
    $html .= '<div class="grid3">';

    //label
    $html .= Form::label($name, $valueLabel, $attributesLabel);

    $html .= '</div>';
    $html .= '<div class="grid9">';

    //input
    $html .= Form::input($type, $name, $value, $attributesInput);

    $html .= '</div>';
    $html .= '<div class="clear"></div>';
    $html .= '</div>';
    return $html;

});


/**
 * Create a HTML select element.
 *
 * @param  string  $name
 * @param  mixed   $options
 * @param  mixed   $selected
 * @param  mixed   $valueLabel
 * @param  array   $attributesInput
 * @param  array   $attributesLabel
 * @return string
 */
Form::macro('nyelect', function($name, $options = array(), $selected = null, $valueLabel = null, $attributesInput = array(), $attributesLabel = array())
{
    $html = '<div class="formRow">';
    $html .= '<div class="grid3">';

    //label
    $html .= Form::label($name, $valueLabel, $attributesLabel);

    $html .= '</div>';
    $html .= '<div class="grid9">';

    //input
    $html .= Form::select($name, $options, $selected, $attributesInput);

    $html .= '</div>';
    $html .= '<div class="clear"></div>';
    $html .= '</div>';
    return $html;

});


/**
 * Create a HTML select element.
 *
 * @param  string  $name
 * @param  mixed   $value
 * @param  mixed   $checked
 * @param  mixed   $valueLabel
 * @param  mixed   $valueInsideLabel
 * @param  array   $attributesInput
 * @param  array   $attributesLabel
 * @return string
 */
Form::macro('nyheckbox', function($name, $value = null, $checked = false, $valueLabel = null, $valueInsideLabel = null, $attributesInput = array(), $attributesLabel = array())
{
    $html = '<div class="formRow">';
    $html .= '<div class="grid3">';

    //label
    $html .= Form::label($name, $valueLabel, $attributesLabel);

    $html .= '</div>';
    $html .= '<div class="grid9 check">';

    //input
    $html .= Form::checkbox($name, $value, $checked, $attributesInput);
    $html .= '<label class="mr20">'. $valueInsideLabel .'</label>';

    $html .= '</div>';
    $html .= '<div class="clear"></div>';
    $html .= '</div>';
    return $html;
});


/**
 * return name or description of given access_type code
 * @param string $code
 * @return string
 */
HTML::macro('access_type', function($code) {
    if($code == 'M')
        return 'Main Navigation';
    elseif($code == 'S')
        return 'Sub Navigation';
    else
        return 'Access Link';
});

/**
 * return account type in human readable format
 */
HTML::macro('account_type', function($code) {
    if($code == 'C')
        return 'Credit Account';
    elseif($code == 'D')
        return 'Debit Account';
    else
        return 'Undefined';
});

/**
 * Main navigation
 */
HTML::macro('main_nav', function() {
    $mainActive = Session::get('active.main.nav');
    $html = '';
    foreach(Auth::navigation() as $menu) {
        $html .= '<li><a href="';
        $html .= URL::to_action($menu['action']);
        $html .= '"';
        if($menu['action'] == $mainActive) {
            $html .= 'class="active"';
        }
        $html .= '><span>';
        $html .= $menu['title'];
        $html .= '</span></a></li>';
    }
    $html .= '<li><a href="' . URL::to_action('/logout') . '"><span>Logout</span></a></li>';
    echo $html;
});

/**
 * Sub navigation
 */
HTML::macro('sub_nav', function() {
    $mainActive = Session::get('active.main.nav');
    $html = '';
    $main = Auth::navigation();
    foreach(Auth::navigation() as $menu) {
        if($menu['action'] == $mainActive) {
            $main = $menu;
        }
    }
    if(isset($main['childs']) && $main['childs'] != null) {
        foreach($main['childs'] as $menu) {
            $html .= '<ul class="subNav"><li ';
            $a = str_replace('/', '@', URI::current());
            if($menu['action'] == $a) {
                $html .= 'class="activeli"';
            }
            $html .= '><a href="' . URL::to_action($menu['action']) . '" class="';
            if($menu['action'] == $a) {
                $html .= 'this ';
            }
            $html .= '"';
            $html .= '><span class="';
            $html .= $menu['image'];
            $html .= '"></span>';
            $html .= $menu['title'];
            $html .= '</a></li></ul>';
        }
    }
    echo $html;

});


/**
 * Alternate navigation (Media Queries 483px)
 */
HTML::macro('alt_nav', function() {
    $mainActive = Session::get('active.main.nav');
    $html = '';
    foreach(Auth::navigation() as $menu) {
        $html .= '<li><a href="';
        if(isset($menu['childs']) && $menu['childs'] != null) {
            $html .= '#';
        } else {
            $html .= URL::to_action($menu['action']);
        }
        $html .= '"';
        if($menu['action'] == $mainActive) {
            $html .= 'id="current"';
        }
        if(isset($menu['childs']) && $menu['childs'] != null) {
            $html .= 'class="exp"';
        }
        $html .= '>' . $menu['title'] . '</a>';
        if(isset($menu['childs']) && $menu['childs'] != null) {
            $html .= '<ul class="leftSubMenu">';
            foreach($menu['childs'] as $child) {
                $html .= '<li><a href="' . URL::to_action($child['action']) . '">';
                $html .= $child['title'];
                $html .= '</a></li>';
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
    }
    $html .= '<li><a href="' . URL::to_action('/logout') . '"><span>Logout</span></a></li>';
    echo $html;
});