<?php

namespace Extendy\Smartyurl;

use App\Models\UrlModel;
use CodeIgniter\Config\Services;

class UrlIdentifier
{
    public function GenerateNewURLIdentifier()
    {
    }

    /**
     * This function check if the given URL Identifier is exists or not
     *
     * @return bool
     */
    public function CheckURLIdentifierExists(string $urlidentifier, int|null $except = null)
    {
        $UrlModel = new UrlModel();
        $query    = $UrlModel->where('url_identifier', $urlidentifier);
        if ($except !== null) {
            $query->where('url_id !=', $except);
        }
        $urlData = $query->first();

        if ($urlData === null) {
            // $urlidentifier not exisys
            $returnvalue = false;
        } else {
            // $urlidentifier  exists
            $returnvalue = true;
        }

        return $returnvalue;
    }

    /**
     * Check if the given url identifier is allowed to use or not
     */
    public function isURLIdentifierallowed(string $urlidentifier): bool
    {
        $urlidentifier = mb_strtolower($urlidentifier);
        $allowed       = true;
        $router        = Services::routes();

        $routes_get                        = $router->getRoutes('get');
        $urlidentifier_exists_as_route_get = array_key_exists($urlidentifier, $routes_get);
        if ($urlidentifier_exists_as_route_get) {
            $allowed = false;
        }

        $routes_post                        = $router->getRoutes('post');
        $urlidentifier_exists_as_route_post = array_key_exists($urlidentifier, $routes_post);
        if ($urlidentifier_exists_as_route_post) {
            $allowed = false;
        }

        return $allowed;
    }
}
