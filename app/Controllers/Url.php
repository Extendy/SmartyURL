<?php

namespace App\Controllers;

use App\Models\UrlHitsModel;
use App\Models\UrlModel;
use App\Models\UrlTagsDataModel;
use App\Models\UrlTagsModel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use CodeIgniter\Shield\Models\UserModel;
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
        $this->urltags          = new UrlTags();
        $this->urlhitsmodel     = new UrlHitsModel();
    }

    /**
     * View Of List All Urls
     *
     * @return string
     */
    public function index()
    {
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error();
        }
        $user_id = user_id();
        $data    = [];
        if (! auth()->user()->can('admin.manageotherurls', 'super.admin')) {
            // that mean I must redirect the user to /url/user/{userid}
            // i will set the filter rule to user instead of route to, but sure you can use redirect()->to
            // if you want
            return redirect()->to('url/user/' . $user_id);
            // or sepcify the user info
            $data['filterrule']  = 'user';
            $data['filtervalue'] = $user_id;
            $data['filtertext']  = lang('Url.urlsUserLinks') . ' ' . smarty_get_user_username($user_id);
        } else {
            $data['filtertext'] = lang('Url.urlsAllLink');
        }

        return view(smarty_view('url/list'), $data);
    }

    /**
     * View of list user urls
     *
     * @param mixed|null $urlOwnerUserId
     *
     * @return string
     */
    public function listuserurls($urlOwnerUserId = null)
    {
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error();
        }
        $user_id = user_id();

        $data['filterrule']  = 'user';
        $data['filtervalue'] = $urlOwnerUserId;
        $data['filtertext']  = lang('Url.urlsUserLinks') . ' ' . smarty_get_user_username($user_id);

        if (! auth()->user()->can('admin.manageotherurls', 'super.admin') && (int) $urlOwnerUserId !== $user_id) {
            return smarty_permission_error(lang('Common.permissionsNoenoughpermissions'), false);
        }
        if ((int) $urlOwnerUserId === $user_id) {
            $data['filtertext'] = lang('Url.urlsMyLink');
        }

        // make sure user $urlOwnerUserId is exists user
        // I place it after the permission check to prevent any potential data bypass.
        $usermodel = new UserModel();
        if ($urlOwnerUserId !== null) {
            $user = $usermodel->find($urlOwnerUserId);
            if (! $user) {
                // user is not exists
                return redirect()->to('dashboard')->with('notice', lang('Users.UserNotFound'));
            }
        }

        return view(smarty_view('url/list'), $data);
    }

    public function listtagurls($tags)
    {
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error();
        }
        $user_id = user_id();

        // @TODO FIX ME I must make sure user is exists
        // @TODO and make sure from permission
        $urltagsmodel  = new UrlTagsModel();
        $tag_info      = $urltagsmodel->getTagInfoById($tags);
        $tag_text_line = '';

        foreach ($tag_info as $tag) {
            $tag_text_line .= ($tag_text_line !== '' ? ' ' : '') . $tag->tag_name;
        }
        $tag_text_line = " '" . $tag_text_line . "'";

        $data['filterrule']  = 'tag';
        $data['filtervalue'] = $tags;
        $data['filtertext']  = lang('Url.urlsTagsLinks') . $tag_text_line;

        // @FIXME , how toe listData will know for which user it will search??
        // THINK That you can use more of filterrule or something like that. to refer for the user

        return view(smarty_view('url/list'), $data);
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

        $user_id = user_id();

        $searchValue = $this->request->getGet('search')['value'] ?? '';

        $draw   = $this->request->getGet('draw');
        $start  = $this->request->getGet('start');
        $length = $this->request->getGet('length');

        // Do not think that the data that comes from the client is always correct
        // so set force max length it  maxUrlListPerPage
        $system_forcemax_length = setting('Smartyurl.maxUrlListPerPage');
        if ($length > setting('Smartyurl.maxUrlListPerPage')) {
            $length = $system_forcemax_length;
        }

        // detect the order by comes from ajax call if submitted
        $columnOrder = $this->request->getGet('order');
        if ($columnOrder !== null) {
            $ajax_column_index = $columnOrder['0']['column'];
            $order_by_dir      = $columnOrder['0']['dir'];

            // Do not think that the data that comes from the client is always correct
            // so switch it to use defaults
            switch ($order_by_dir) {
                case 'asc':
                    $order_by_rule = 'asc';
                    break;

                case 'desc':
                    $order_by_rule = 'desc';
                    break;

                default:
                    $order_by_rule = 'desc';
                    break;
            }

            // i will know the column name from get
            $ajax_columns              = $this->request->getGet('columns');
            $order_by_ajax_column_name = $ajax_columns[$ajax_column_index]['name'];

            // Do not think that the data that comes from the client is always correct
            // so switch it to use defaults
            switch ($order_by_ajax_column_name) {
                case 'url_identifier':
                    $order_by = 'url_identifier';
                    break;

                case 'url_hits':
                    $order_by = 'url_hitscounter';
                    break;

                default:
                    $order_by = 'url_id';
                    break;
            }
        } else {
            exit('order column is null');
        }

        $filterrule  = $this->request->getGet('filterrule') ?? '';
        $filtervalue = $this->request->getGet('filtervalue') ?? '';

        switch ($filterrule) {
            case 'user':
                // list urls for single user
                if (! auth()->user()->can('admin.manageotherurls', 'super.admin') && (int) $filtervalue !== $user_id) {
                    return smarty_permission_error(lang('Common.permissionsNoenoughpermissions'), true);
                }
                $urlAllCount      = $this->urlmodel->getUrlsForUser($filtervalue, $start, $length, null, $order_by, $order_by_rule, 'count');
                $results          = $this->urlmodel->getUrlsForUser($filtervalue, $start, $length, $searchValue, $order_by, $order_by_rule, 'data');
                $filterAllnumRows = $this->urlmodel->getUrlsForUser($filtervalue, $start, $length, $searchValue, $order_by, $order_by_rule, 'count');
                break;

            case 'tag':
                // @TODO @FIXME you need to get the url for the given tag
                if (auth()->user()->can('admin.manageotherurls', 'super.admin')) {
                    // i wil return all tags urls , for all users
                    $tag_user_id = null;
                } else {
                    // i will return only user urls for that tag
                    $tag_user_id = $user_id;
                }
                $urlAllCount      = $this->urltagsdatamodel->getUrlInfoForTagId($filtervalue, $tag_user_id, null, $start, $length, $order_by, $order_by_rule, 'count');
                $results          = $this->urltagsdatamodel->getUrlInfoForTagId($filtervalue, $tag_user_id, $searchValue, $start, $length, $order_by, $order_by_rule, 'data');
                $filterAllnumRows = $this->urltagsdatamodel->getUrlInfoForTagId($filtervalue, $tag_user_id, $searchValue, $start, $length, $order_by, $order_by_rule, 'count');
                break;

            default:
                // list urls for all users
                // this checks for permission is important to avoid anyone to call the url controller directly
                if (! auth()->user()->can('admin.manageotherurls', 'super.admin')) {
                    return smarty_permission_error(lang('Common.permissionsNoenoughpermissions'), true);
                }
                $urlAllCount      = $this->urlmodel->getUrlsForUser(null, $start, $length, null, $order_by, $order_by_rule, 'count');
                $results          = $this->urlmodel->getUrlsForUser(null, $start, $length, $searchValue, $order_by, $order_by_rule, 'data');
                $filterAllnumRows = $this->urlmodel->getUrlsForUser(null, $start, $length, $searchValue, $order_by, $order_by_rule, 'count');
                break;
        }

        $records = [];
        // Fetch the results

        $langurlListTags = lang('Url.urlListTags');

        // check if $results !== null before do foreach
        if ($results !== null) {
            foreach ($results as $result) {
                if ($result->url_title === '') {
                    $urlTitle = lang('Url.UrlTitleNoTitle');
                } else {
                    $urlTitle = esc($result->url_title);
                }
                // i will get the url tags
                $url_tags_json  = $this->urltags->getUrlTagsCloud($result->url_id);
                $url_tags_array = json_decode($url_tags_json);
                $url_tags       = '';
                if (count($url_tags_array) > 0) {
                    foreach ($url_tags_array as $tag) {
                        $tag_id   = $tag->tag_id;
                        $tag_name = $tag->value;

                        $url_tags .= "<a class='btn btn-sm btn-outline-dark mx-1' href='" . site_url('url/tag/' . $tag_id) . "'>{$tag_name}</a>";
                    }
                    $url_tags = "<div class='mt-1'>{$langurlListTags}:" . $url_tags . '</div>';
                }

                if (auth()->user()->can('admin.manageotherurls', 'super.admin')) {
                    // he is manager so i must let him know the url owner
                    $url_owner_id = smarty_get_user_username($result->url_user_id);
                    $url_owner    = "<div class='mt-1'>" . lang('Url.UrlOwner') . ": <a class='link-dark' href='" . site_url("url/user/{$result->url_user_id}") . "'> {$url_owner_id}</a></div>";
                } else {
                    $url_owner = '';
                }

                $result->url_identifier = esc($result->url_identifier);
                // $result->url_id],$result->url_title,$result->url_hitscounter
                $Go_Url = esc(smarty_detect_site_shortlinker() . $result->url_identifier);

                // addtional options for the url
                $url_addtionaloptions = '<div class="d-flex justify-content-end"><button id="deleteurlButton" data-url-go="' . $Go_Url . '"  data-url-id="' . $result->url_id . '" type="button" class=" btn btn-outline-danger flex-shrink-0">
                                        <i class="bi bi-trash"></i>
                                    </button></div>';

                // even if url shared , but if setting share url between users is disabled the icon shared will not display
                if ($result->url_shared && setting('Smartyurl.url_can_be_shared_between_users')) {
                    $url_shared_line = "<i class='bi bi-universal-access text-danger' title='" . lang('Url.UrlIsSharedShort') . "'></i>";
                } else {
                    $url_shared_line = null;
                }

                $records[] = [
                    'url_id_col'         => $result->url_id,
                    'url_identifier_col' => "{$url_shared_line} <a class='link-dark listurls-link' href='" . site_url("url/view/{$result->url_id}") . "' data-url='{$Go_Url}'>{$result->url_identifier}</a>
                                            <a title='" . lang('Url.UpdateUrlSubmitbtn') . "' href='" . site_url("url/edit/{$result->url_id}") . "' class='link-dark edit-link'><i class='bi bi-pencil edit-link-btn'></i></a>
                                            <i title='" . lang('Url.CopyURL') . "' class='bi bi-clipboard copy-button' data-content='{$Go_Url}' data-target='link2'></i>    ",
                    'url_title_col' => " {$urlTitle}
                    <a target='_blank' title='" . lang('Url.visitOriginalUrl') . ' ' . create_nice_url_for_show($result->url_targeturl) . "' href='{$result->url_targeturl}' class='link-dark edit-link'><i class='bi bi-box-arrow-up-right'></i></a>
                    ",
                    'url_hits_col'         => "<a class='text-secondary' href='" . site_url("url/hits/{$result->url_id}") . "'>" . $result->url_hitscounter . '</a>',
                    'url_id'               => $result->url_id,
                    'url_tags'             => $url_tags,
                    'url_owner'            => $url_owner,
                    'url_addtionaloptions' => $url_addtionaloptions,
                ];
            }
        }
        // $results is null so no return value

        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $urlAllCount,
            'recordsFiltered' => $filterAllnumRows,
            'data'            => $records,
        ];

        return $this->response->setJSON($data);
    }

    public function view($UrlId)
    {
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
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

        if ($urlData === null) {
            // url not exsists in dataase
            return redirect()->to('dashboard')->with('error', lang('Url.urlNotFoundShort'));
        }

        // i will check the user permission , does he allowed to access this url info
        $userCanAccessUrl = $this->smartyurl->userCanAccessUrlInfo($url_id, (int) $urlData['url_user_id']);
        if (! $userCanAccessUrl) {
            return smarty_permission_error('It not your URL 😉😉😉');
        }
        $urlTagsCloud = $UrlTags->getUrlTagsCloud($url_id);
        // $urlTagsCloud = '[{"value":"tag1","tag_id":"3"},{"value":"tag2","tag_id":"27"},{"value":"tag3","tag_id":"24"}]';

        $Go_Url             = esc(smarty_detect_site_shortlinker() . $urlData['url_identifier']);
        $url_owner_username = smarty_get_user_username($urlData['url_user_id']);

        $data           = [];
        $data['url_id'] = $urlData['url_id'];
        if ($urlData['url_title'] === '') {
            $urlData['url_title'] = lang('Url.UrlTitleNoTitle');
        }
        $data['url_owner_username']           = $url_owner_username;
        $data['url_shered_with_system_users'] = $urlData['url_shared'] ? '<span class="text-danger">' . lang('Common.btnYes') . '</span>' : lang('Common.btnNo');
        $data['url_title']                    = esc($urlData['url_title']);
        $data['url_targeturl']                = esc($urlData['url_targeturl']);
        $data['url_identifier']               = esc($urlData['url_identifier']);
        $data['url_hitscounter']              = $urlData['url_hitscounter'];

        $data['created_at'] = $urlData['created_at'];
        $data['updated_at'] = $urlData['updated_at'];
        $data['go_url']     = $Go_Url;

        $data['url_tags'] = json_decode($urlTagsCloud);

        // i will get the redirect conditions
        $redirectConditions     = json_decode($urlData['url_conditions']);
        $data['condition']      = null;
        $data['condition_text'] = null;

        if ($redirectConditions !== null) {
            // there is a url redirect condition
            $data['condition'] = $redirectConditions->condition;

            switch ($data['condition']) {
                case 'location':
                    $data['condition_text'] = lang('Url.ByvisitorsGeolocation');
                    break;

                case 'device':
                    $data['condition_text'] = lang('Url.ByvisitorsDevice');
                    break;

                default:
                    $data['condition_text'] = $data['condition'];
            }

            $data['conditions'] = $redirectConditions->conditions;
        } else {
            $data['condition_text'] = lang('Url.urlInfoNoRecdirectCondition');
        }

        // dd($redirectConditions);

        // i will try to get the last 25 hits of the url
        $lasthits         = $this->urlhitsmodel->getLast25Hits($urlData['url_id']);
        $data['lasthits'] = $lasthits;

        return view(smarty_view('url/urlinfo'), $data);
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
        $originalUrl = $this->request->getPost('originalUrl');
        if (! $this->smartyurl->isValidURL($originalUrl)) {
            return redirect()->to('url/new')->withInput()->with('error', lang('Url.urlInvalidOriginal'));
        }

        $identifier = smarty_remove_whitespace_from_url_identifier($this->request->getPost('UrlIdentifier'));
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
        $urlTitle          = $this->request->getPost('UrlTitle');
        $redirectCondition = $this->request->getPost('redirectCondition');
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

        // check if url shared feat enabled to let the user share the urls with other if he want
        $shared_url_feat_enabled = setting('Smartyurl.url_can_be_shared_between_users');
        if ($shared_url_feat_enabled) {
            // check if the user choose to share the url with users.
            $UrlShared = (bool) $this->request->getPost('UrlShared') ?? false;
        } else {
            // sharing feat not enabled globally
            $UrlShared = false;
        }

        // try to insert the url into db
        // Define the data to be inserted
        $url_table_data = [
            'url_identifier' => $identifier,
            'url_user_id'    => user_id(),
            'url_title'      => $urlTitle,
            'url_targeturl'  => $originalUrl,
            'url_conditions' => $json_urlConditions,
            'url_shared'     => $UrlShared,
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
                        $geofinalurl[] = $finalUrl;
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
                        $devicefinalurl[] = $finalUrl;
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
            'originalUrl'       => esc($urlData['url_targeturl']),
            'UrlTitle'          => esc($urlData['url_title']),
            'UrlIdentifier'     => esc($urlData['url_identifier']),
            'UrlShared'         => esc($urlData['url_shared']),
            'urlTags'           => esc($urlTagsCloud), // i must get the URLTags
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
        $originalUrl = $this->request->getPost('originalUrl');
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
            return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('Url.urlIdentifierNotAllowed', [$identifier]));
        }

        // urlTitle
        $urlTitle          = $this->request->getPost('UrlTitle');
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

        // check if url shared feat enabled to let the user share the urls with other if he want
        $shared_url_feat_enabled = setting('Smartyurl.url_can_be_shared_between_users');
        if ($shared_url_feat_enabled) {
            // check if the user choose to share the url with users.
            $UrlShared = (bool) $this->request->getPost('UrlShared') ?? false;
        } else {
            // sharing feat not enabled globally
            $UrlShared = false;
        }

        // try to update the url data on db

        $updatedData = [
            'url_identifier' => $identifier,
            'url_title'      => $urlTitle,
            'url_targeturl'  => $originalUrl,
            'url_conditions' => $json_urlConditions,
            'url_shared'     => $UrlShared,
            // Add more fields as needed
        ];

        $UrlModel->update($url_id, $updatedData);

        if ($UrlModel->affectedRows() > 0) {
            // updated ok

            // i will check tags and update any changes
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
            return redirect()->to("url/view/{$UrlId}")->with('success', lang('Url.UpdateURLOK'));
        }

        // updated error
        return redirect()->to("url/edit/{$UrlId}")->withInput()->with('error', lang('Url.UpdateURLError'));
    }

    public function hitslist($UrlId)
    {
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error();
        }

        $url_id = (int) esc(smarty_remove_whitespace_from_url_identifier($UrlId));
        if ($url_id === 0) {
            // url_id given is not valid id
            return redirect()->to('dashboard')->with('notice', lang('Url.urlError'));
        }
        $urlData = $this->urlmodel->where('url_id', $url_id)->first();

        if ($urlData === null) {
            // url not exsists in dataase
            return redirect()->to('dashboard')->with('error', lang('Url.urlNotFoundShort'));
        }

        // i will check the user permission , does he allowed to access this url info
        $userCanAccessUrl = $this->smartyurl->userCanAccessUrlInfo($url_id, (int) $urlData['url_user_id']);
        if (! $userCanAccessUrl) {
            return smarty_permission_error('It not your URL 😉😉😉');
        }
        $Go_Url = esc(smarty_detect_site_shortlinker() . $urlData['url_identifier']);

        $data = [];

        $data['lang']            = session('lang');
        $data['url_id']          = (int) $urlData['url_id'];
        $data['url_identifier']  = esc($urlData['url_identifier']);
        $data['url_hitscounter'] = $urlData['url_hitscounter'];
        $data['go_url']          = $Go_Url;

        return view(smarty_view('url/hitslist'), $data);
    }

    public function hitslistData($urlId)
    {
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error(lang('Common.permissionsNoenoughpermissions'), true);
        }

        $url_id           = (int) $urlId;
        $userCanAccessUrl = $this->smartyurl->userCanAccessUrlInfo($url_id);
        if (! $userCanAccessUrl) {
            return smarty_permission_error('It is not your URL 😉😉😉', true);
        }

        $draw   = $this->request->getGet('draw');
        $start  = $this->request->getGet('start');
        $length = $this->request->getGet('length');

        // Do not think that the data that comes from the client is always correct
        // so set force max length it  maxUrlListPerPage
        $system_forcemax_length = setting('Smartyurl.maxUrlListPerPage');
        if ($length > setting('Smartyurl.maxUrlListPerPage')) {
            $length = $system_forcemax_length;
        }

        $columnOrder = $this->request->getGet('order');
        if ($columnOrder !== null) {
            $ajax_column_index = $columnOrder['0']['column'];
            $order_by_dir      = $columnOrder['0']['dir'];

            // Do not think that the data that comes from the client is always correct
            // so switch it to use defaults
            switch ($order_by_dir) {
                case 'asc':
                    $order_by_rule = 'asc';
                    break;

                case 'desc':
                    $order_by_rule = 'desc';
                    break;

                default:
                    $order_by_rule = 'desc';
                    break;
            }

            // i will know the column name from get
            $ajax_columns              = $this->request->getGet('columns');
            $order_by_ajax_column_name = $ajax_columns[$ajax_column_index]['name'];

            // echo $order_by_ajax_column_name;
            // echo $order_by_rule;

            switch ($order_by_ajax_column_name) {
                case 'hit_date':
                    $order_by = 'urlhit_at';
                    break;

                default:
                    $order_by = 'urlhit_urlid';
                    break;
            }
        } else {
            $order_by      = 'urlhit_id';
            $order_by_rule = 'desc';
        }

        $urlAllCount      = $this->urlhitsmodel->getHitsByUrlId($url_id, null, null, 'urlhit_urlid', 'desc', false);
        $filterAllnumRows = $urlAllCount;
        $results          = $this->urlhitsmodel->getHitsByUrlId($url_id, $start, $length, $order_by, $order_by_rule, true);
        $records          = [];
        if ($results !== null) {
            foreach ($results as $result) {
                $records[] = [
                    'hit_date_col'      => $result->urlhit_at,
                    'hit_ip_col'        => $result->urlhit_ip,
                    'hit_country_col'   => $result->urlhit_country,
                    'hit_device_col'    => $result->urlhit_visitordevice,
                    'hit_useragent_col' => esc($result->urlhit_useragent),
                    'hit_finalurl_col'  => urldecode($result->urlhit_finaltarget),
                ];
            }
        }

        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $urlAllCount, // $urlAllCount
            'recordsFiltered' => $filterAllnumRows, // $filterAllnumRows
            'data'            => $records,
        ];

        return $this->response->setJSON($data);
    }

    /**
     * Show all urls hits view file
     *
     * @param $UrlId
     *
     * @return void
     */
    public function urlshitslist()
    {
        // check the permissions
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error();
        }

        $data = [];

        $data['lang'] = session('lang');

        return view(smarty_view('url/allhitslist'), $data);
    }

    /**
     * hits list for all urls
     *
     * @return void
     */
    public function urlshitslistData()
    {
        // check the permissions
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            return smarty_permission_error(lang('Common.permissionsNoenoughpermissions'), true);
        }

        // if he is notmal user i will show hits for his urls only
        // if he is manageotherurls or superadmin i will show all hits
    }

    /**
     * This function generates QR Code for tge given URL id
     *
     * @return mixed
     */
    public function generateQRCode(int $UrlId)
    {
        // set response type
        $response = service('response');
        $response->setContentType('image/svg+xml');

        $error = '';
        if (! auth()->user()->can('url.access', 'admin.manageotherurls', 'super.admin')) {
            $error = 'Permission error';

            return $response->setBody(smarty_svg_error($error));
        }

        $UrlModel = new UrlModel();
        $url_id   = (int) esc(smarty_remove_whitespace_from_url_identifier($UrlId));

        if ($url_id === 0) {
            // url_id given is not valid id
            $error = lang('Url.urlError');

            return $response->setBody(smarty_svg_error($error));
        }
        $urlData = $UrlModel->where('url_id', $url_id)->first();

        if ($urlData === null) {
            // url not exsists in dataase
            $error = lang('Url.urlNotFoundShort');

            return $response->setBody(smarty_svg_error($error));
        }

        // i will check the user permission , does he allowed to access this url info
        $userCanAccessUrl = $this->smartyurl->userCanAccessUrlInfo($url_id, (int) $urlData['url_user_id']);
        if (! $userCanAccessUrl) {
            $error = 'not your URL 😉';

            return $response->setBody(smarty_svg_error($error));
        }

        $Go_Url = esc(smarty_detect_site_shortlinker() . $urlData['url_identifier']);

        // prepare for the filename
        // remove any special chars and white spaces will be _
        $pattern  = '/[^\w\d\.,;!?@#$%^&*()_+-=:<>"\'\/\\\[\]{}|`~]+/u';
        $filename = setting('Smartyurl.siteName') . "_{$UrlId}.svg";
        $filename = str_replace(' ', '_', $filename);
        $filename = preg_replace($pattern, '', $filename);

        // if query download i will set Content Disposition to attachment
        $download = (int) $this->request->getGet('download'); // Access the 'download' parameter
        if ($download === 1) {
            $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } else {
            $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        }

        // now I will generate QR Code
        $options = new QROptions();
        // i will  smarty detect qr version by text length
        $options->version          = smarty_smart_detect_qrversion($Go_Url);
        $options->outputType       = QROutputInterface::MARKUP_SVG;
        $options->outputBase64     = false;
        $options->drawLightModules = true;
        $options->circleRadius     = 0.4;
        $out                       = (new QRCode($options))->render($Go_Url);

        // now i will return the image
        return $response->setBody($out);
    }

    public function delUrl(int $UrlId)
    {
        $response = [];
        if (! auth()->user()->can('url.manage', 'admin.manageotherurls', 'super.admin')) {
            $response['error'] = lang('Common.permissionsNoenoughpermissions');

            return $this->response->setJSON($response);
        }
        $url_id = (int) esc(smarty_remove_whitespace_from_url_identifier($UrlId));
        if ($url_id === 0) {
            $response['error'] = lang('Url.urlDelInvalidURL');
        }
        // i will check if the url id is exists or not
        $urlData = $this->urlmodel->where('url_id', $url_id)->first();
        if ($urlData === null) {
            // url not exsists in dataase
            $response['error'] = lang('Url.urlNotFoundShort');

            return $this->response->setJSON($response);
        }
        // i will see if the current user can manage this url
        $userManageUrl = $this->smartyurl->userCanManageUrl($url_id);
        if (! $userManageUrl) {
            $response['error'] = lang('Url.urlDelCannotDelthisUrlDuePermissions');

            return $this->response->setJSON($response);
        }
        // i will try to delete the url

        $delurl = $this->urlmodel->deleteUrlById($url_id);
        if ($delurl > 0) {
            // deleted
            $response['status'] = 'deleted';
        } else {
            // not deleted or error
            $response['error'] = lang('Url.urlDelError');
        }

        return $this->response->setJSON($response);
    }
}
