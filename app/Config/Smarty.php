<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Smarty extends BaseConfig
{
    public $smarty_name        = 'SmartyURL';
    public $smarty_online_repo = 'https://extendy.net/SmartyURL';
    public $smarty_version     = '0.0.1-dev';

    /**
     * @var string contain the file name of jquery supported version eg jquery-3.7.1 without js
     *             for example if jquery-3.7.1 specified that mean there is a file with name
     *             jquery-3.7.1.js and jquery-3.7.1.min.js in cdn/js/jquery/{]
     *             if you don't show what you should use , keep it as default jquery-3.7.1
     */
    public $jquery_supported = 'jquery-3.7.1';
}
