<?php

namespace App\Controllers;

use App\Models\UrlModel;
use App\Models\UrlTagsDataModel;
use Extendy\Smartyurl\SmartyUrl;
use Extendy\Smartyurl\UrlConditions;
use Extendy\Smartyurl\UrlIdentifier;
use Extendy\Smartyurl\UrlTags;

/**
 * Class BaseController
 *
 * Url Controller Deal with URL of SmartyURL
 *
 * For security, be sure to declare any new methods as protected or private.
 */
class Url extends BaseController
{
    public function index()
    {
        if (! auth()->user()->can('url.access')) {
            return smarty_permission_error();

            exit(1);
        }
        // also the user must own this url or he is superadmin

        d('this is the index of url');
    }

    public function view($UrlId)
    {
        d($UrlId);
    }

    public function new()
    {
        if (! auth()->user()->can('url.new')) {
            // return  redirect()->route('permissions')->with('error', lang('Auth.notEnoughPrivilege'));
            return smarty_permission_error();
        }
        $data = [];
        // no need to pass $worldCountries
        // $worldCountries         = new WorldCountries();
        // $data['worldcountries'] = $worldCountries->getCountriesList();

        return view(smarty_view('url/new'), $data);
    }

    public function newAction()
    {
        if (! auth()->user()->can('url.new')) {
            return smarty_permission_error();
        }
        $SmartyURL = new SmartyUrl();
        // check if original url is valid url
        $originalUrl = esc($this->request->getPost('originalUrl'));
        if (! $SmartyURL->isValidURL($originalUrl)) {
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlInvalidOriginal'));
        }

        $identifier = esc(smarty_remove_whitespace_from_url_identifier($this->request->getPost('UrlIdentifier')));
        if (! preg_match(Config('Smartyurl')->urlIdentifierpattern, $identifier)) {
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlIdentifierPatternError', [Config('Smartyurl')->urlIdentifierpattern]));
        }
        $UrlIdentifier = new UrlIdentifier();
        if ($UrlIdentifier->CheckURLIdentifierExists($identifier)) {
            // url idenitifier is exists on db
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlIdentifieralreadyExists', [$identifier]));
        }

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
            // i will check all conditions final url is valid urls or not
            if (! $urlConditions->validateConditionsFinalURls($json_urlConditions)) {
                return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlSomeFinalURLsIsNotValid'));
            }
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
        $inserted_url_id = $UrlModel->getInsertID();

        if ($inserted_url_id > 0) {
            // i will try to add the tags for not exists tags
            // first of all i will generate tags
            // urlTags is json or ""
            // example [{"value":"massarcloud","tag_id":"1"},{"value":"mshannaq"}]
            // not exists tags have no tag_id , but we will not depend on that (as it coming from user input form)
            // and everytime we will check
            $urlTags = $this->request->getPost('urlTags');
            if ($urlTags !== '') {
                $tags          = [];
                $urlTags_array = json_decode($urlTags);

                foreach ($urlTags_array as $tag) {
                    $tags[] = $tag->value;
                }
                // we must deal with tag as it not ""
                $urltabs_class   = new UrlTags();
                $try_insert_tags = $urltabs_class->tryInsertTags($tags);
                // if $try_insert_tags size is 0 so no new tags added to db
                // else there is some tags added to db and $try_insert_tags is array  for 'tag_id' of the added tags
                // as [0] ['value' => "{$tag}", 'tag_id' => $UrlTagsModel->getInsertID()]
                //    [1] ['value' => "{$tag}", 'tag_id' => $UrlTagsModel->getInsertID()]
                //    ...etc
                // now I will insert the tags for this url in urltagsdata db table
                $UrlTagsDataModel = new UrlTagsDataModel();

                foreach ($urlTags_array as $tag) {
                    if (isset($tag->tag_id)) {
                        // $tag->tag_id is defined so it is already has its id
                        // $tag->value contains name of tag
                        $UrlTagsDataModel->insert(
                            [
                                'url_id' => $inserted_url_id,
                                'tag_id' => $tag->tag_id,
                            ]
                        );
                    }
                }

                // insert the new tags that's created on this session
                foreach ($try_insert_tags as $newtag) {
                    $UrlTagsDataModel->insert(
                        [
                            'url_id' => $inserted_url_id,
                            'tag_id' => $newtag['tag_id'],
                        ]
                    );
                }
            }

            return redirect()->to('url/view/' . $inserted_url_id)->with('notice', lang('Url.AddNewURLAdded'));
        }

        return redirect()->to('url/new')->withInput()->with('notice', lang('Account.WrongCurrentPassword'));
    }

    public function edit($UrlId)
    {
        // @TODO @FIXME  user cannot edit others URLs unless he is can super.admin
        // check permissions
        if (! auth()->user()->can('url.edit')) {
            return smarty_permission_error();
        }
        $UrlModel = new UrlModel();
        $UrlTags  = new UrlTags();
        $url_id   = (int) esc(smarty_remove_whitespace_from_url_identifier($UrlId));
        if ($url_id === 0) {
            // url_id given is not valid id
            return redirect()->to('dashboard')->with('notice', lang('Url.urlError'));
        }
        $urlData = $UrlModel->where('url_id', $url_id)->first();
        // dd($urlData);
        if ($urlData === null) {
            // url not exsists in dataase
            return redirect()->to('dashboard')->with('error', lang('Url.urlNotFoundShort'));
        }
        $urlTagsCloud = $UrlTags->getUrlTagsCloud($url_id);
        // $urlTagsCloud = '[{"value":"tag1","tag_id":"3"},{"value":"tag2","tag_id":"27"},{"value":"tag3","tag_id":"24"}]';

        // will try to get the url redirection conditions
        $urlRedirectConditions = json_decode($urlData['url_conditions']);
        if ($urlRedirectConditions === null) {
            // there is n conditions
            $redirectCondition = null;
        } else {
            switch ($urlRedirectConditions->condition) {
                case 'location':
                    $redirectCondition = 'geolocation';
                    $geocountry        = [];
                    $geofinalurl       = [];

                    foreach ($urlRedirectConditions->conditions as $conditionarray) {
                        foreach ($conditionarray as $country => $finalUrl) {
                            $geocountry[]  = $country;
                            $geofinalurl[] = $finalUrl;
                        }
                    }
                    $data['geocountry'] = $geocountry;
                    // var_dump($data['geocountry']);
                    // die;
                    $data['geofinalurl'] = $geofinalurl;
                    break;

                case 'device':
                    $redirectCondition = 'device';
                    $devicecond        = [];
                    $devicefinalurl    = [];

                    foreach ($urlRedirectConditions->conditions as $conditionarray) {
                        foreach ($conditionarray as $devicename => $devicearray) {
                            foreach ($devicearray as $finalUrlarray) {
                                foreach ($finalUrlarray as $device => $finalUrl) {
                                    switch ($device) {
                                        case 'windows':
                                            $devicecond[]     = 'windowscomputer';
                                            $devicefinalurl[] = $finalUrl;
                                            break;

                                        case 'andriod':
                                            $devicecond[]     = 'andriodsmartphone';
                                            $devicefinalurl[] = $finalUrl;
                                            break;

                                        case 'iphone':
                                            $devicecond[]     = 'applesmartphone';
                                            $devicefinalurl[] = $finalUrl;
                                            break;
                                    }
                                }
                            }
                        }
                    }
                    break;

                default:
                    // null
                    $redirectCondition = null;
            }
        }

        // now i will try to know the exact conditions
        // @TODO MSHANNAQHERE
        // dd($urlRedirectConditions);

        // know define $data which will be passwd to the view
        $data = [
            'editUrlAction'     => site_url("/url/edit/{$url_id}"),
            'originalUrl'       => urldecode($urlData['url_targeturl']),
            'UrlTitle'          => $urlData['url_title'],
            'UrlIdentifier'     => $urlData['url_identifier'],
            'urlTags'           => $urlTagsCloud, // i must get the URLTags
            'redirectCondition' => $redirectCondition,
        ];
        if ($redirectCondition === 'geolocation') {
            $data['geocountry']  = $geocountry;
            $data['geofinalurl'] = $geofinalurl;
        }
        if ($redirectCondition === 'device') {
            $data['device']         = $devicecond;
            $data['devicefinalurl'] = $devicefinalurl;
        }

        return view(smarty_view('url/new'), $data);
    }

    public function editAction($UrlId)
    {
        // @TODO @FIXME  user cannot edit others URLs unless he is can super.admin

        // check permissions
        if (! auth()->user()->can('url.edit')) {
            return smarty_permission_error();
        }
        $url_id = (int) esc(smarty_remove_whitespace_from_url_identifier($UrlId));
        if ($url_id === 0) {
            // url_id given is not valid id
            return redirect()->to('dashboard')->with('notice', lang('Url.urlError'));
        }
        $UrlModel = new UrlModel();
        $urlData  = $UrlModel->where('url_id', $url_id)->first();
        dd($urlData);
        // user cannot edit others URLs unless he is can super.admin
        echo 'edit url action';
        d($UrlId);
    }
}
