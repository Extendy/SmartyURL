<?php

namespace App\Controllers;

use App\Models\UrlModel;
use RuntimeException;

class Go extends BaseController
{
    public function index($param)
    {
    }

    /**
     * @param string $identifier
     *
     * @return int|object|null
     */
    public function go($identifier)
    {
        $response   = service('response');
        $identifier = esc(smarty_remove_whitespace_from_url_identifier($identifier));
        // try to get the url data from database using it is identifier
        $UrlModel = new UrlModel();
        $urlData  = $UrlModel->where('url_identifier', $identifier)->first();
        if ($urlData === null) {
            // url identifier not found in database , 404 not found will be shown
            $response->setStatusCode(404);
            $viewContent = view(smarty_view(Config('Smartyurl')->views['urlNotFound']));
            $response->setBody($viewContent);

            return $response;
        }

        // the first target of the url before apply any conditions
        $finalTargetURL = $urlData['url_targeturl'];
        if ($urlData['url_conditions'] === null) {
            // this mean return the target url as is because it does not have conditions
            // @TODO how to let this use one reidrect at the end (not here) with $finalTargetURL
            $finalTargetURL = $urlData['url_targeturl'];
        } else {
            // there is a url_conditions
            $url_conditions = json_decode($urlData['url_conditions']);
            if ($url_conditions === null) {
                // invalid json comes from db, how come..
                throw new RuntimeException("Invalid json data in url_conditions for  url '{$identifier}'");
            }

            switch ($url_conditions->condition) {
                case 'location':
                    $visitorCountry = smarty_get_visitor_ip_country();

                    foreach ($url_conditions->conditions as $condition) {
                        if (property_exists($condition, $visitorCountry)) {
                            // final traget found , @FIXME  while target found should i continue or just exit the foreach
                            $finalTargetURL = $condition->{$visitorCountry};
                        }
                    }
                    break;

                case 'device':
                    dd('this is a device condition');
                    dd($url_conditions);
                    break;

                default:
                    // invalid condition
                    // why we use RuntimeException error, I think we shoud use the $urlData['url_targeturl'] instead
                    // throw new RuntimeException("Invalid condition '{$url_conditions->condition}' in url_conditions for  url '{$identifier}'");
                    // return null;
                    $finalTargetURL = $urlData['url_targeturl'];
                    break;
            }
        }

        if ($finalTargetURL === null) {
            // finalTarget is null, so i will use the normal target
            $finalTargetURL = $urlData['url_targeturl'];
        }

        // Use the redirect() method to redirect to the external URL
        return $response->redirect($finalTargetURL, 'auto', Config('Smartyurl')->http_response_codes_when_redirect); // You can adjust the status code and 'auto' option as needed
        // @TODO @FIXME ***** i will do the following to url before go
        // store the visit into url hits table
        // then i can redirect the user
    }
}
