<?php

namespace App\Controllers;

use App\Models\UrlModel;
use App\Models\UrlTagsDataModel;
use Extendy\Smartyurl\UrlConditions;
use Extendy\Smartyurl\UrlIdentifier;
use Extendy\Smartyurl\UrlTags;
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
        // dd($this->request->getPost("UrlIdentifier"));
        $identifier = esc(smarty_remove_whitespace_from_url_identifier($this->request->getPost('UrlIdentifier')));
        if (! preg_match(Config('Smartyurl')->urlIdentifierpattern, $identifier)) {
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlIdentifierPatternError', [Config('Smartyurl')->urlIdentifierpattern]));
        }
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
            // i will convert it to object to keep it as $urlTags_array
            $try_insert_tags = $try_insert_tags;
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

            // insert the new tags thats created on this session
            foreach ($try_insert_tags  as $newtag) {
                $UrlTagsDataModel->insert(
                    [
                        'url_id' => $inserted_url_id,
                        'tag_id' => $newtag['tag_id'],
                    ]
                );
            }
        }

        d($_POST);
        if ($inserted_url_id > 0) {
            return redirect()->to('url/view/' . $inserted_url_id)->with('notice', lang('Url.AddNewURLAdded'));
        }

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
