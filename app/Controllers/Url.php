<?php

namespace App\Controllers;

use App\Models\UrlModel;
use Extendy\Smartyurl\UrlConditions;
use Extendy\Smartyurl\UrlIdentifier;
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
        $data = [];
        // no need to pass $worldCountries
        // $worldCountries         = new WorldCountries();
        // $data['worldcountries'] = $worldCountries->getCountriesList();

        return view(smarty_view('url/new'), $data);
    }

    public function newAction()
    {
        // dd($this->request->getPost("UrlIdentifier"));
        $identifier    = esc(smarty_remove_whitespace_from_url_identifier($this->request->getPost('UrlIdentifier')));
        $UrlIdentifier = new UrlIdentifier();
        if ($UrlIdentifier->CheckURLIdentifierExists($identifier)) {
            // url idenitifier is exists on db
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlIdentifieralreadyExists', [$identifier]));
        }
        // originalUrl
        $originalUrl = esc($this->request->getPost('originalUrl'));
        // urlTitle
        $urlTitle          = esc($this->request->getPost('UrlTitle'));
        $redirectCondition = esc($this->request->getPost('redirectCondition'));
        if ($redirectCondition === 'device' || $redirectCondition === 'geolocation') {
            // url_conditions
            // /...................here will be conditions
            $conditions_array = [
                'device'         => $this->request->getPost('device'),
                'devicefinalurl' => $this->request->getPost('devicefinalurl'),
                'geocountry'     => $this->request->getPost('geocountry'),
                'geofinalurl'    => $this->request->getPost('geofinalurl'),
            ];
            $urlConditions      = new UrlConditions();
            $json_urlConditions = $urlConditions->josonizeUrlConditions($conditions_array);
        } else {
            // no $redirectCondition
            $json_urlConditions = null;
        }

        // try to insert the url into db
        // Define the data to be inserted
        $url_table_data = [
            'url_identifier' => $identifier,
            'url_user_id'    => user_id(),
            'url_title'      => $urlTitle,
            'url_targeturl'  => $originalUrl,
            'url_conditions' => $json_urlConditions,
        ];

        $UrlModel     = new UrlModel();
        $inserted_url = $UrlModel->insert($url_table_data, false);
        if (! $inserted_url) {
            return redirect()->to('url/new')->withInput()->with('notice', lang('Account.WrongCurrentPassword'));
        }
        d($inserted_url);
        $inserted_url_id = $UrlModel->getInsertID();

        // i will try to add the tags for not exists tags
        // first of all i will generate tags
        // urlTags is json
        // example [{"value":"massarcloud","tag_id":"1"},{"value":"mshannaq"}]
        // not exists tags have no tag_id , but we will not depend on that (as it coming from user input form)
        // and everytime we will check

        d($inserted_url_id);
        dd($_POST);

        return redirect()->to('url/new')->withInput()->with('notice', lang('Account.WrongCurrentPassword'));
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
