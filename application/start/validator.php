<?php

Validator::register('required_select', function($attribute, $value, $parameters)
{
    return $value == '0' || $value == 0;
});