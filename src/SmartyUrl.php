<?php

namespace Extendy\Smartyurl;

/**
 * SmartyURL Class
 */
class SmartyUrl
{
    /**
     * This function checks the given string is valid URL or not
     */
    public function isValidURL(string $url): bool
    {
        // Regex pattern for a valid URL
        // $regex = '/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}((:[0-9]{1,5})?\\/.*)?$/i';
        // to fix #46 and detrmine http://example.com?dldld is valid url.
        $regex = '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/.*)?(\?.*)?$/i';

        return (bool) (preg_match($regex, $url));
    }
}
