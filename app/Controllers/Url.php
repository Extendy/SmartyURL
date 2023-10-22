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
    public function __construct()
    {
        $this->smartyurl        = new SmartyUrl();
        $this->urltagsdatamodel = new UrlTagsDataModel();
        $this->urlmodel         = new UrlModel();
    }

    public function index()
    {
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error();
        }

        return view(smarty_view('url/list'));
        d('this is the index of url');
    }

    /**
     * This function return the json data while listing urls
     *
     * @return void
     */
    public function listData()
    {
        // @TODO check login and permissions
        // dd("f");
        // @TODO @FIXME YOu must add maxiums that cannot br ovwerwritttn to avoid attacks

        $searchValue = $this->request->getGet('search')['value'] ?? '';

        $draw   = $this->request->getGet('draw');
        $start  = $this->request->getGet('start');
        $length = $this->request->getGet('length');

        if ($searchValue !== '') {
            $this->urlmodel->like('url_identifier', $searchValue)
                ->orLike('url_id', $searchValue)
                ->orLike('url_title', $searchValue)
                ->orLike('url_targeturl', $searchValue);
        }

        $query = $this->urlmodel->select('*')  // Select the columns you need
            ->limit($length, $start)  // Apply the limit and start offset
            ->get();

        // I need to know for this filter how many result will be
        if ($searchValue !== '') {
            $this->urlmodel->like('url_identifier', $searchValue)
                ->orLike('url_id', $searchValue)
                ->orLike('url_title', $searchValue)
                ->orLike('url_targeturl', $searchValue);
        }
        $query2  = $this->urlmodel->select('*');
        $numRows = $query2->countAllResults();

        $records = [];
        // Fetch the results
        $results     = $query->getResult();
        $query_count = count($results);

        $urlAllCount = $this->urlmodel->countAll();

        // print_r($results);
        foreach ($results as $result) {
            if ($result->url_title === '') {
                $urlTitle = lang('Url.UrlTitleNoTitle');
            } else {
                $urlTitle = $result->url_title;
            }

            // $result->url_id],$result->url_title,$result->url_hitscounter
            $records[] = [
                "<a class='link-dark listurls-link' href='" . site_url("url/view/{$result->url_id}") . "'>{$result->url_identifier}</a>
                 <a title='" . lang('Url.UpdateUrlSubmitbtn') . "' href='" . site_url("url/edit/{$result->url_id}") . "' class='link-dark edit-link'><i class='bi bi-pencil-fill'></i></a>
                ",
                " {$urlTitle}
                 <a target='_blank' title='" . lang('Url.visitOriginalUrl') . ' ' . $result->url_targeturl . "' href='{$result->url_targeturl}' class='link-dark edit-link'><i class='bi bi-box-arrow-up-right'></i></a>
                ",
                $result->url_hitscounter,
            ];
        }

        // $totalRecords = $this->urlmodel->getTotalRecordsCount();
        /* $records = [
             [1, "John Doe", "john@example.com"],
             [2, "Jane Smith", "jane@example.com"],
             [3, "3Jane Smith", "jane@example.com"],
             [4, "4Jane Smith", "jane@example.com"],
             [5, "5Jane Smith", "jane@example.com"],
             // Add more rows as needed
         ];*/

        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $urlAllCount,
            'recordsFiltered' => $numRows,
            'data'            => $records,
        ];

        return $this->response->setJSON($data);
    }

    public function view($UrlId)
    {
        d($UrlId);
    }

    public function new()
    {
        if (! auth()->user()->can('url.new', 'super.admin')) {
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
        if (! auth()->user()->can('url.new', 'super.admin')) {
            return smarty_permission_error();
        }
        // check if original url is valid url
        $originalUrl = esc($this->request->getPost('originalUrl'));
        if (! $this->smartyurl->isValidURL($originalUrl)) {
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

        if (! $UrlIdentifier->isURLIdentifierallowed($identifier)) {
            // url identifier is not allowed
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlIdentifierNotAllowed', [$identifier]));
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

                foreach ($urlTags_array as $tag) {
                    if (isset($tag->tag_id)) {
                        // $tag->tag_id is defined so it is already has its id
                        // $tag->value contains name of tag
                        $this->urltagsdatamodel->insert(
                            [
                                'url_id' => $inserted_url_id,
                                'tag_id' => $tag->tag_id,
                            ]
                        );
                    }
                }

                // insert the new tags that's created on this session
                foreach ($try_insert_tags as $newtag) {
                    $this->urltagsdatamodel->insert(
                        [
                            'url_id' => $inserted_url_id,
                            'tag_id' => $newtag['tag_id'],
                        ]
                    );
                }
            }

            return redirect()->to('url/view/' . $inserted_url_id)->with('notice', lang('Url.AddNewURLAdded'));
        }

        return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlError'));
    }

    public function edit($UrlId)
    {
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
        // i will check the user permission , does he allowed to edit this url
        $userCanManageUrl = $this->smartyurl->userCanManageUrl($url_id, (int) $urlData['url_user_id']);
        if (! $userCanManageUrl) {
            return smarty_permission_error();
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

                    foreach ($urlRedirectConditions->conditions as $country => $finalUrl) {
                        $geocountry[]  = $country;
                        $geofinalurl[] = urldecode($finalUrl);
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

                    foreach ($urlRedirectConditions->conditions as $device => $finalUrl) {
                        $devicecond[]     = $device;
                        $devicefinalurl[] = urldecode($finalUrl);
                    }
                    break;

                default:
                    // null
                    $redirectCondition = null;
            }
        }

        // now i will try to know the exact conditions
        // know define $data which will be passwd to the view
        $data = [
            'UrlId'             => $url_id,
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
        $url_id = (int) esc(smarty_remove_whitespace_from_url_identifier($UrlId));
        if ($url_id === 0) {
            // url_id given is not valid id
            return redirect()->to('dashboard')->with('notice', lang('Url.urlError'));
        }
        $UrlModel = new UrlModel();
        $urlData  = $UrlModel->where('url_id', $url_id)->first();
        // check if the given url id is exists or not
        if ($urlData === null) {
            // url not exsists in dataase
            return redirect()->to('dashboard')->with('error', lang('Url.urlNotFoundShort'));
        }
        // i will check the user permission , does he allowed to edit this url
        $userCanManageUrl = $this->smartyurl->userCanManageUrl($url_id, (int) $urlData['url_user_id']);
        if (! $userCanManageUrl) {
            return smarty_permission_error();
        }

        // user cannot edit others URLs unless he is can super.admin or admin.manageurls
        // check if original url is valid url
        $originalUrl = esc($this->request->getPost('originalUrl'));
        if (! $this->smartyurl->isValidURL($originalUrl)) {
            return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('Url.urlInvalidOriginal'));
        }
        // check the identifier is standard
        $identifier = esc(smarty_remove_whitespace_from_url_identifier($this->request->getPost('UrlIdentifier')));
        if (! preg_match(Config('Smartyurl')->urlIdentifierpattern, $identifier)) {
            return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('Url.urlIdentifierPatternError', [Config('Smartyurl')->urlIdentifierpattern]));
        }
        // check if identifier is existing for another URL?
        $UrlIdentifier = new UrlIdentifier();
        if ($UrlIdentifier->CheckURLIdentifierExists($identifier, $UrlId)) {
            // url idenitifier is exists on db
            return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('Url.urlIdentifieralreadyExists', [$identifier]));
        }

        if (! $UrlIdentifier->isURLIdentifierallowed($identifier)) {
            // url identifier is not allowed
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlIdentifierNotAllowed', [$identifier]));
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
                return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('Url.urlSomeFinalURLsIsNotValid'));
            }
        } else {
            // no $redirectCondition
            $json_urlConditions = null;
        }

        // try to update the url data on db

        $updatedData = [
            'url_identifier' => $identifier,
            'url_title'      => $urlTitle,
            'url_targeturl'  => $originalUrl,
            'url_conditions' => $json_urlConditions,
            // Add more fields as needed
        ];

        $UrlModel->update($url_id, $updatedData);

        if ($UrlModel->affectedRows() > 0) {
            // updated ok

            // i will check tags and update any changes
            // samsam
            // first of all i will delete all url tags
            $delresult = $this->urltagsdatamodel->delUrlTags($url_id);
            // now i will enter the tags again
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

                foreach ($urlTags_array as $tag) {
                    if (isset($tag->tag_id)) {
                        // $tag->tag_id is defined so it is already has its id
                        // $tag->value contains name of tag
                        $this->urltagsdatamodel->insert(
                            [
                                'url_id' => $url_id,
                                'tag_id' => $tag->tag_id,
                            ]
                        );
                    }
                }

                // insert the new tags that's created on this session
                foreach ($try_insert_tags as $newtag) {
                    $this->urltagsdatamodel->insert(
                        [
                            'url_id' => $url_id,
                            'tag_id' => $newtag['tag_id'],
                        ]
                    );
                }
            }

            // return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('OK'))->with('updated',"yes");
            return redirect()->back()->with('success', lang('Url.UpdateURLOK'));
        }

        // updated error
        return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('Url.UpdateURLError'));
    }
}
