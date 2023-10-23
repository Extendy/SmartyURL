<?php

namespace App\Controllers;

use App\Models\UrlModel;
use Extendy\Smartyurl\SmartyUrlDevice;
use Extendy\Smartyurl\UrlHits;
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
        $url_id         = $urlData['url_id'];
        $finalTargetURL = $urlData['url_targeturl'];
        $visitorCountry = smarty_get_visitor_ip_country();
        if ($urlData['url_conditions'] === null) {
            // this mean return the target url as is because it does not have conditions
            $finalTargetURL = $urlData['url_targeturl'];
        } else {
            // there is a url_conditions in db
            $url_conditions = json_decode($urlData['url_conditions']);
            if ($url_conditions === null) {
                // invalid json comes from db, how come..
                throw new RuntimeException("Invalid json data in url_conditions for  url '{$identifier}'");
            }

            switch ($url_conditions->condition) {
                case 'location':
                    // sample json data
                    // {"condition": "location", "conditions": [{"JO": "https://extendy.net/?jo"}, {"SA": "https://extendy.net/?sa"}]}

                    foreach ($url_conditions->conditions as $condition_country => $condition_url) {
                        if ($condition_country === $visitorCountry) {
                            $finalTargetURL = $condition_url;
                            // exit the foreach loop while we found the condition and no need to keep searching.
                            break;
                        }
                    }

                    break;

                case 'device':
                    // sample json data
                    // {"condition": "device", "conditions": [{"computer": [{"windows": "www.microsoft.com"}, {"linux": "www.linux.com"}], "smartphone": [{"andriod": "www.andriod.com"}, {"iphone": "www.apple.com"}]}]}
                    $smartyurldevicedetect = new SmartyUrlDevice();

                    // for each device condition i will check it it equal the current visitor device type
                    foreach ($url_conditions->conditions as $condition => $finalURL) {
                        $verifydevicecondition = $smartyurldevicedetect->verifyDeviceCondition($condition, $finalURL);
                        if ($verifydevicecondition !== null) {
                            $finalTargetURL = $verifydevicecondition;
                        }
                    }

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
            // finalTarget is null,I cannot get the $finalTargetURL from url conditions
            // So I will use the default url_targeturl
            $finalTargetURL = $urlData['url_targeturl'];
        }

        // I will store this hit into urlhits
        $urlhits                                = new UrlHits();
        $visit_collected_data['visitorCountry'] = $visitorCountry;
        $visit_collected_data['finalUrl']       = $finalTargetURL;
        $urlhits->recordUrlHit($url_id, $visit_collected_data);

        // Use the redirect() method to redirect to the external URL
        return $response->redirect($finalTargetURL, 'auto', Config('Smartyurl')->http_response_codes_when_redirect); // You can adjust the status code and 'auto' option as needed
    }
}
