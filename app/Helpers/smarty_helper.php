<?php

declare(strict_types=1);

/**
 * SmartyURL helpers
 * it is loadded automaticlly from Config/Autoload.php , public $helpers = [...
 */

use Config\Services;

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
    function smarty_permission_error(string $error = '')
    {
        $data['errorMsg'] = esc($error);

        return view(smarty_view('errors/permissions'), $data);
    }
}
