<?php

declare(strict_types=1);

/**
 * SmartyURL helpers
 * it is loadded automaticlly from Config/Autoload.php , public $helpers = [...
 */

use CodeIgniter\HTTP\Response;
use CodeIgniter\Shield\Models\UserModel;
use Config\Services;
use IP2Location\IpTools;

if (! function_exists('smarty_current_lang')) {
    /**
     * Get the current language shortname (2 digits , e.g : en , ar ...)
     *
     * @return string
     */
    function smarty_current_lang()
    {
        $language = Services::language();
        // Get active locale
        $activeLocale = $language->getLocale();

        return setting('Smartyurl.locales')[$activeLocale]['shortname'];
    }
}

if (! function_exists('smarty_current_lang_direction')) {
    /**
     * Get the current language direction rtl ltr ..
     *
     * @return string
     */
    function smarty_current_lang_direction()
    {
        $language = Services::language();
        // Get active locale
        $activeLocale = $language->getLocale();

        return setting('Smartyurl.locales')[$activeLocale]['direction'];
    }
}

if (! function_exists('smarty_current_lang_dotdirection')) {
    /**
     * returns .rtl of the language if the current language is one of Right to left languages
     * to include fit css and js files.
     *
     * @return string
     */
    function smarty_current_lang_dotdirection()
    {
        $direction = strtolower(smarty_current_lang_direction());
        if ($direction === 'rtl') {
            // RTL language
            $returnvalue = '.rtl';
        } else {
            $returnvalue = null;
        }

        return $returnvalue;
    }
}

/**
 * return smarty cdn path ended with /
 *
 * @TODO make sure the cdn must ended with / before return it
 */
if (! function_exists('smarty_cdn')) {
    function smarty_cdn()
    {
        return setting('Smartyurl.cdn');
    }
}

/**
 * this will reyurn the jquery file
 */
if (! function_exists('smarty_include_jquery')) {
    function smarty_include_jquery($min = true)
    {
        return setting('Smartyurl.cdn') . 'js/jquery/' . config('Smarty')->jquery_supported . '.min.js';
    }
}

/**
 * return the given view name with a prefix of smarty theme name
 */
if (! function_exists('smarty_view')) {
    function smarty_view(string $viewName)
    {
        $themeFolder = config('Smartyurl')->themeFolder;

        return $themeFolder . '/' . $viewName;
    }
}

if (! function_exists('smarty_pagetitle')) {
    function smarty_pagetitle(string $pageTitle)
    {
        if ($pageTitle === null) {
            $title = setting('smartyurl.siteName');
        } else {
            $title = $pageTitle . ' - ' . setting('smartyurl.siteName');
        }

        return $title;
    }
}

if (! function_exists('smarty_permission_error')) {
    function smarty_permission_error(string $error = '', $json = false)
    {
        $errorCode        = 403; // which means HTTP response Forbidden
        $data['errorMsg'] = esc($error);
        if (! $json) {
            $response = service('response');
            $response->setStatusCode($errorCode);
            $response->setBody(view(smarty_view('errors/permissions'), $data));

            return $response;
        }
        $response = service('response');
        $response->setStatusCode($errorCode);
        $response->setJSON(['error' => 'Permissions error ' . esc($error)]);

        return $response;
    }
}

if (! function_exists('smarty_remove_whitespace_from_url_identifier')) {
    function smarty_remove_whitespace_from_url_identifier(string $url_identifier)
    {
        // Use a regular expression to remove all white spaces
        $pattern     = '/\s+/';
        $replacement = '';
        $cleanString = preg_replace($pattern, $replacement, $url_identifier);

        return trim($cleanString);
    }
}

if (! function_exists('smarty_detect_site_shortlinker')) {
    function smarty_detect_site_shortlinker()
    {
        // while @ this stage we support only one site for shortlinks so we will use the baseURL
        $string = setting('app.baseURL');
        if (! str_ends_with($string, '/')) {
            $string .= '/';
        }

        return $string;
    }
}

if (! function_exists('smarty_get_visitor_ip_country')) {
    /**
     * This function try to determine the given IP country code
     *
     * @param string $ip valid ip addresse (IPv4 or IPv6)
     *
     * @return string Country name in ALPHA-2 code , for example US UK SA JO
     *
     * @throws Exception
     */
    function smarty_get_visitor_ip_country($ip = null)
    {
        if ($ip === null) {
            // Call the getVisitorIp() method on the instance
            $ipTools = new IPTools();
            $ip      = $ipTools->getVisitorIp();
        }
        $ip2locationdb                 = new \IP2Location\Database(Config('Smartyurl')->ip2location_bin_file, \IP2Location\Database::FILE_IO);
        $visitorIp2locationRecords     = $ip2locationdb->lookup($ip, \IP2Location\Database::ALL);
        $visitorIp2locationcountryCode = $visitorIp2locationRecords['countryCode'];

        return trim($visitorIp2locationcountryCode);
    }
}

if (! function_exists('smarty_get_user_username')) {
    function smarty_get_user_username($userId)
    {
        $shieldUserModel = new UserModel();
        $user            = $shieldUserModel->find($userId);
        if ($user) {
            $username = $user->username;
        } else {
            // User not found
            $username = null;
        }

        return $username;
    }
}

if (! function_exists('smarty_smart_detect_qrversion')) {
    function smarty_smart_detect_qrversion($url)
    {
        return setting('Smartyurl.qrCodeVersion');
    }
}

if (! function_exists('smarty_svg_error')) {
    function smarty_svg_error($text)
    {
        return '<svg width="200" height="100" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="black" />
  <text x="50%" y="50%" fill="white" font-size="20" text-anchor="middle" alignment-baseline="middle">' . $text . '</text>
</svg>';
    }
}

if (! function_exists('create_nice_url_for_show')) {
    /**
     * Create a nice URL to show on screen and not for real use on visits
     *
     * @return mixed|string
     */
    function create_nice_url_for_show($original_url, $max_length = 50)
    {
        // Check if the original URL is longer than the specified maximum length
        if (mb_strlen($original_url, 'UTF-8') > $max_length) {
            // Trim the URL to the maximum length and add "..." at the end
            $nice_url = mb_substr($original_url, 0, $max_length - 3, 'UTF-8') . '...';
        } else {
            // If the URL is within the maximum length, use the original URL
            $nice_url = $original_url;
        }

        return $nice_url;
    }
}
