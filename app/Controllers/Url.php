<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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
        //This is the Index of URLS
        if (\auth()->loggedIn()) {
            $user = \auth()->user();
            echo "<pre>";
            print_r($user->username);
            echo "</pre>";
        }
        d("this is the index of url");
    }

    public function new(){
        if (! auth()->user()->can('url.create')) {
            //return  redirect()->route('permissions')->with('error', lang('Auth.notEnoughPrivilege'));
            return "permissions error";
        }

        return view(smarty_view('url/new'));

    }

    public function newAction(){
        echo "url new action";

    }

    /**
     * @FIXME this is for test remove me after testing
     * @return string
     */
    public function none()
    {
        return view(smarty_view('none'));
    }


}
