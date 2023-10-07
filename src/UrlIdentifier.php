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
    public function CheckURLIdentifierExists(string $urlidentifier)
    {
        $UrlModel = new UrlModel();
        $urlData  = $UrlModel->where('url_identifier', $urlidentifier)->first();
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
