<?php

namespace App\Controllers;

use App\Models\UrlModel;

class Go extends BaseController
{
    public function index($param)
    {
    }

    /**
     * @return int|object|null
     */
    public function go($identifier)
    {
        $session    = session();
        $response   = service('response');
        $identifier = esc(smarty_remove_whitespace_from_url_identifier($identifier));
        $UrlModel   = new UrlModel();
        $urlData    = $UrlModel->where('url_identifier', $identifier)->first();
        if ($urlData === null) {
            $response->setStatusCode(404);
            $viewContent = view(smarty_view(Config('Smartyurl')->views['urlNotFound']));
            $response->setBody($viewContent);

            return $response;
        }
        // the first target of the url before apply any conditions
        $url_targeturl = $urlData['url_targeturl'];
        // what is the beaic , the basic is to do header location to target_url
        // header("location: {$url_targeturl}");

        // Use the redirect() method to redirect to the external URL
        return $response->redirect($url_targeturl, 'auto', Config('Smartyurl')->http_response_codes_when_redirect); // You can adjust the status code and 'auto' option as needed
        // @TODO @FIXME ***** i will do the following to url before go
        // check the conditions to see what is the final target
        // store toe visit into url hits table
        // then i can redirect the user
    }
}
