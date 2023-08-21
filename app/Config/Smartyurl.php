<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Smartyurl extends BaseConfig
{
    /**
     * @var string Default Site Name
     */
    public $siteName = "SmartyURL";

    /**
     * @var string path of (static folder content) cdn or URL and it must end WITH a trailing slash /
     * keep it as is if you dont have external cdn, if you want to use CDN enter the url of the cdn
     * **** remember it must end with / ****
     */
    public $cdn = '/static/';

    /**
     * @var bool Minify HTML while out put or not
     */
    public $minifyHtmloutput = true;
}