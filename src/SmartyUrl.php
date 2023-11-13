<?php

namespace Extendy\Smartyurl;

use App\Models\UrlModel;

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
        $regex = '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/[^\s]*)?(\?[^\s]*)?$/i';

        return (bool) (preg_match($regex, $url));
    }

    /**
     * Check if the current logged-in user has management rights for the specified URL.
     * If $urlOwnerUserId is not provided, it will be retrieved from the database.
     * permissions needed
     *  url.manage or admin.manageotherurls and sure or he is super.admin
     *
     * @param int $urlId
     * @param int $urlOwnerUserId
     */
    public function userCanManageUrl($urlId, $urlOwnerUserId = null): bool
    {
        if ($urlOwnerUserId === null) {
            // i will get the url data to know its owner
            $UrlModel = new UrlModel();
            $url_id   = (int) esc(smarty_remove_whitespace_from_url_identifier($urlId));
            $urlData  = $UrlModel->where('url_id', $url_id)->first();
            if ($urlData === null) {
                return false;
            }
            $urlOwnerUserId = (int) $urlData['url_user_id'];
        }
        // we will start from large permission to the lowest permission
        if (auth()->user()->can('super.admin')) {
            return true;
        }
        if (auth()->user()->can('admin.manageotherurls')) {
            return true;
        }

        // the above permissions are for superuser and admins and can deal with all urls not just there urls.
        // but url.manage permission means his own urls only
        // check it this url is his own url and his usergroup has url.manage permissions
        return (bool) (auth()->user()->can('url.manage') && ($urlOwnerUserId === user_id()));
    }

    /**
     * Check if the current logged-in user Can Access Url info for the specified URL.
     * If $urlOwnerUserId is not provided, it will be retrieved from the database.
     * permissions needed
     *  url.access or admin.manageotherurls and sure or he is super.admin
     *
     * @param int $urlId
     * @param int $urlOwnerUserId
     */
    public function userCanAccessUrlInfo($urlId, $urlOwnerUserId = null): bool
    {
        if ($urlOwnerUserId === null) {
            // i will get the url data to know its owner
            $UrlModel = new UrlModel();
            $url_id   = (int) esc(smarty_remove_whitespace_from_url_identifier($urlId));
            $urlData  = $UrlModel->where('url_id', $url_id)->first();
            if ($urlData === null) {
                return false;
            }
            $urlOwnerUserId = (int) $urlData['url_user_id'];
        }
        // we will start from large permission to the lowest permission
        if (auth()->user()->can('super.admin')) {
            return true;
        }
        if (auth()->user()->can('admin.manageotherurls')) {
            return true;
        }

        // the above permissions are for superuser and admins and can deal with all urls not just there urls.
        // but url.manage permission means his own urls only
        // check it this url is his own url and his usergroup has url.manage permissions
        return (bool) (auth()->user()->can('url.access') && ($urlOwnerUserId === user_id()));
    }
}
