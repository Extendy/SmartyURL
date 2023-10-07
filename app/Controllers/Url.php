<?php

namespace App\Controllers;

use Extendy\Smartyurl\WorldCountries;

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
        $worldCountries         = new WorldCountries();
        $data['worldcountries'] = $worldCountries->getCountriesList();

        return view(smarty_view('url/new'), $data);
    }

    public function newAction()
    {
        return redirect()->to('url/new')->withInput()->with('error', lang('Account.WrongCurrentPassword'));
        echo 'url new action';
        dd($_POST);
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
