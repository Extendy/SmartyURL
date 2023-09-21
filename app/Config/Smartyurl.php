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

    /** -------------------------------------------------------------------------
     * Please DO NOT modify uder this line unless you know what you are doing
     * ----------------------------------------------------------------------------
     * -----------------------------------------------------------------------------
     * ----------------------------------------------------------------------------
     */

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
