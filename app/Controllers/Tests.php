<?php
/**
 * This Controller is for tests only
 * it is not a part of real production application.
 */

namespace App\Controllers;

use IP2Location\IpTools;

/**
 * Class BaseController
 *
 * Url Controller Deal with URL of SmartyURL
 *
 * For security be sure to declare any new methods as protected or private.
 */
class Tests extends BaseController
{
    public function index()
    {
        $ipTools = new IPTools();

        // Call the getVisitorIp() method on the instance
        $visitorIp = $ipTools->getVisitorIp();
        echo $visitorIp;
        d('this is the index methos of tests controller');
    }
}
