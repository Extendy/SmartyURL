<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * Url Controller Deal with URL of SmartyURL
 *
 * For security be sure to declare any new methods as protected or private.
 */
class Url extends BaseController
{
    public function index()
    {
        d('this is the index of url');
    }

    public function new()
    {
        if (! auth()->user()->can('url.new')) {
            // return  redirect()->route('permissions')->with('error', lang('Auth.notEnoughPrivilege'));
            return smarty_permission_error();

            exit(1);
        }

        return view(smarty_view('url/new'));
    }

    public function newAction()
    {
        echo 'url new action';
    }

    /**
     * @FIXME this is for test remove me after testing
     *
     * @return string
     */
    public function none()
    {
        return view(smarty_view('none'));
    }
}
