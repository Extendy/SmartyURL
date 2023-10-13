<?php

namespace Extendy\Smartyurl;

use App\Models\UrlModel;

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
}
