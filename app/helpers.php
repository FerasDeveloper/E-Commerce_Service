<?php

if (!function_exists('authUser')) {
    function authUser()
    {
        return request()->get('auth_user');
    }
}
