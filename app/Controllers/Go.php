<?php

namespace App\Controllers;

use App\Models\UrlModel;
use Exception;
use Extendy\Smartyurl\SmartyUrlDevice;
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
                    $visitorCountry = smarty_get_visitor_ip_country();

                    foreach ($url_conditions->conditions as $condition) {
                        if (property_exists($condition, $visitorCountry)) {
                            // final traget found
                            $finalTargetURL = $condition->{$visitorCountry};
                            // exit the foreach loop while we found the condition and no need to keep searching.
                            break;
                        }
                    }

                    break;

                case 'device':
                    // sample json data
                    // {"condition": "device", "conditions": [{"computer": [{"windows": "www.microsoft.com"}, {"linux": "www.linux.com"}], "smartphone": [{"andriod": "www.andriod.com"}, {"iphone": "www.apple.com"}]}]}
                    $smartyurldevicedetect = new SmartyUrlDevice();
                    $visitorDevice         = $smartyurldevicedetect->detectVistorDeviceType();
                    if ($visitorDevice === 'tablet' || $visitorDevice === 'phone') {
                        // phone and tablets called smart phones so it is 'smartphone'
                        // we do not need to now wht it is exactly phone or tablet
                        // So we will call it smartphone for non computer devs (which is phone and tablets)
                        $visitorDevice = 'smartphone';
                    }

                    foreach ($url_conditions->conditions as $condition) {
                        if (property_exists($condition, $visitorDevice)) {
                            // final target found
                            $finalTargetURL = $condition->{$visitorDevice};
                            if (is_array($finalTargetURL)) {
                                // that mean the $finalTargetURL is not a string
                                // example 'computer' ->
                                //        windows-> https://www.zoho.com/mail/mobile/?windowscomputer
                                //        linux->https://www.zoho.com/mail/mobile/?linuxcomputer
                                $thefinalURL = '';

                                foreach ($finalTargetURL as $devicetype) {
                                    $knownoperator = $smartyurldevicedetect->tryToKnowKnownOperator();
                                    if (property_exists($devicetype, $knownoperator)) {
                                        // final traget found
                                        echo "operator  found {$knownoperator}";
                                        $thefinalURL    = $devicetype->{$knownoperator};
                                        $finalTargetURL = $thefinalURL;
                                        // d($thefinalURL);
                                        // exit the foreach loop while we found the condition and no need to keep searching.
                                        break;
                                    }
                                    echo "operator not found {$knownoperator}";

                                    // d($devicetype);
                                }
                                if ($thefinalURL === '') {
                                    // $thefinalURL not found in foreach ,  we will use the default url
                                    $finalTargetURL = null; // this will set $finalTargetURL to null .. later will be set to default
                                }
                            } else {
                                // $finalTargetURL is string , and it cannot be happened in 'device' case
                                throw new Exception('finalTargetURL is string .The finalTargetURL should be a JSON object in the devicetype stage, which is more logical than a string');
                            }

                            // exit the foreach loop while we found the condition and no need to keep searching.
                            // @TODO do i need to break for device while it may be smartphone and andriod?!!!
                            break;
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

        // Use the redirect() method to redirect to the external URL
        return $response->redirect($finalTargetURL, 'auto', Config('Smartyurl')->http_response_codes_when_redirect); // You can adjust the status code and 'auto' option as needed
        // @TODO @FIXME ***** i will do the following to url before go
        // store the visit into url hits table
        // then i can redirect the user
    }
}
