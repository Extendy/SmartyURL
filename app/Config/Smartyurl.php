<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Smartyurl extends BaseConfig
{
    /**
     * @var string Default Site Name
     */
    public $siteName = 'SmartyURL';

    /**
     * --------------------------------------------------------------------
     *
     * @var bool Minify HTML while out put or not, by default false
     *           --------------------------------------------------------------------
     */
    public $minifyHtmloutput = false;

    /**
     * @var string path of (static folder content) cdn or URL and it must end WITH a trailing slash /
     *             keep it as is if you dont have external cdn, if you want to use CDN enter the url of the cdn
     *             **** remember it must end with / ****
     */
    public $cdn = '/static/';

    /**
     * @var string the path of ip2location db bin file
     *             if you buy the database from ip2location ip2location.com use the bin file that
     *             you purchased instead of this free one
     *             the free bin file which included with SmartyURL by default is LITE and not
     *             for all IPS in the world and not very updated.
     *             so if you want to use country filter buy the database from ip2location.com
     *             you can store the bin file where you want to change his path below
     *
     * @default is $ip2location_bin_file = VENDORPATH."ip2location/ip2location-php/data/IP2LOCATION-LITE-DB1.BIN";
     */
    public $ip2location_bin_file =  APPPATH . "../datastore/". 'IP2Location/IP2LOCATION-LITE-DB1.IPV6.BIN';

    /**
     * @var bool if url tags is shared between site users?
     *           if you enable this setting so any tags in system will be shared between users
     *           even if that tag was created by a user, all users will see the tag
     *           --> if enabled they only see tags , NO LINKS
     *           this useful  when are running a private site for your employees and you want
     *           then to share tags cloud between them.
     *           by default, we disable it until you decide to enable it by making the value true
     *
     *           KEEP IT FALSE IF YOU DO NOT NEED SHARED TAGS BETWEEN USERS
     *           FOR LARGE & PUBLIC SITES ENABLE THIS CAN MAKE PERFORMANCE AND PRIVACY PROBLEMS
     *
     * @default false
     */
    public $urltags_shared_between_users = false;

    /**
     * @var int when try to return the tags cloud for the users what is the maximum number the tags cloud shoud
     *          contains
     *          we think 500 tag is enough, if you want you can increase or decrease it.
     *
     * @default 500
     */
    public $urlTagsCloudLimit = 500;

    /**
     * The default number of URLs to display on a single page when listing URLs.
     *
     * don't set this value bigger than $maxUrlListPerPage value
     */
    public int $defautltUrlListPerPage = 25;

    /**
     * The maximum number of URLs to display on a single page when listing URLs.
     *
     * This limit is set to 100 by default to prevent overloading the page with too many URLs.
     * - We advise not lower than 100 or view issues may happen
     */
    public int $maxUrlListPerPage = 100;

    /**
     * The allowed pattern for Url Identifier
     *
     * @default '/^[A-Za-z0-9][A-Za-z0-9_\-\.]{0,49}$/'
     * which is:
     *          Starts with an alphanumeric character (A-Z, a-z, 0-9).
     *          Followed by any combination of alphanumeric characters
     *          (including underscores _ , hyphens - , and periods . ) up to a maximum length of 50 characters.
     *          and no other special character allowded.
     *
     * @var string
     */
    // with allow of .
    // public $urlIdentifierpattern = '/^[A-Za-z0-9][A-Za-z0-9_\-\.\s]{0,48}[A-Za-z0-9]$/';
    // without allow of .
    public $urlIdentifierpattern = '/^[A-Za-z0-9][A-Za-z0-9_\-\s]{0,48}[A-Za-z0-9]$/';

    /** -------------------------------------------------------------------------
     * Please DO NOT modify uder this line unless you know what you are doing
     * ----------------------------------------------------------------------------
     * -----------------------------------------------------------------------------
     * ----------------------------------------------------------------------------
     */

    /**
     * @var int maxiumum requests are made to a url Go service (redirect) per minute IP address, after
     *          that number to url go will return 429 error
     *          this is to protect the go services from being attacked.
     *
     * @default is 15
     * means 15 requests / minute per IP address.
     * we think it is enough if you need more, try to increase it.
     */
    public $urlWebRateLimit = 15;

    /**
     * @var int maxiumum requests are made website per minute per IP address , after
     *          that number to url go will return 429 error
     *          this is to protect the go services from begin attacked.
     *
     * @default is 100
     * means 100 requests / minute per IP address. we think it is enough , if you need more try ti increase it.
     */
    public $siteRateLimit = 100;

    /**
     * @var string default theme folder in app/Views
     *             the defaul is basic
     *             * if you create a new theme change this to your theme folder name
     */
    public $themeFolder = 'basic';

    /**
     * @var string[] the views used on smarty ,see helper smarty_view()
     *
     * @TODO i think this is not used yet
     */
    public $views = [
        // the main layout which used for logged in users
        /*  'layout' => 'layout',
        // other views
        'dashboard' => 'dashboard',*/
        'urlNotFound' => 'url/404.php',
    ];

    /**
     * --------------------------------------------------------------------
     * Customize the DB group used for each model
     * --------------------------------------------------------------------
     */
    public ?string $DBGroup = null;

    public array $dbtables = [
        'urls'        => 'urls',
        'urltags'     => 'urltags',
        'urltagsdata' => 'urltagsdata',
        'urlhits'     => 'urlhits',
    ];

    /**
     * --------------------------------------------------------------------
     * define support languages layout and direction
     * All supported languages must be defined here
     * --------------------------------------------------------------------
     */
    public array $locales = [
        'ar' => [
            'shortname'    => 'ar',
            'direction'    => 'rtl',
            'basealign'    => 'right',
            'inversealign' => 'left',
            'name'         => 'Arabic',
            'nativename'   => 'العربية',
        ],
        'en' => [
            'shortname'    => 'en',
            'direction'    => 'ltr',
            'basealign'    => 'left',
            'inversealign' => 'right',
            'name'         => 'English',
            'nativename'   => 'English',
        ],
    ];

    /**
     * @var int the appropriate HTTP response code when reirect the url , you can use 301 , 302 default it 301
     *          default it 302 for best results
     *          301 means Moved Permanently (Permanent Redirect)
     *          Use this response code if the short URL will always redirect to the same destination. It indicates to search engines and browsers that the redirection is permanent, and they should update their records accordingly.     *
     *          302 means  Found (Temporary Redirect):
     *          Use this response code if you intend to keep the short URL active for a temporary period. It tells search engines and browsers that the redirection is temporary, and they should continue to check the original URL for updates.
     */
    public $http_response_codes_when_redirect = 301;
}
