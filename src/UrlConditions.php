<?php

namespace Extendy\Smartyurl;

class UrlConditions
{
    /**
     * This function will try to convert url conditions to JSON format
     *
     * @param string $conditions contains array device, array devicefinalurl, array geocountry, array geofinalurl
     *
     * @return string JSON Format
     */
    public function josonizeUrlConditions(array $conditions)
    {
        // device
        if ($conditions['device'] !== null) {
            // this is device condition
            $device          = $conditions['device'];
            $devicefinalurl  = $conditions['devicefinalurl'];
            $devicecondition = [];

            for ($i = 0; $i < count($device); $i++) {
                $devicecondition[esc($device[$i])] = esc($devicefinalurl[$i]);
            }
            $finalarray               = [];
            $finalarray['condition']  = 'device';
            $finalarray['conditions'] = $devicecondition;
            $final_json               = json_encode($finalarray);
        }

        // geolocation
        if ($conditions['geocountry'] !== null) {
            // this is geocountry condition
            $geocountry   = esc($conditions['geocountry']);
            $geofinalurl  = esc($conditions['geofinalurl']);
            $geocondition = [];

            for ($i = 0; $i < count($geocountry); $i++) {
                $geocondition[$geocountry[$i]] = $geofinalurl[$i];
            }
            $finalarray               = [];
            $finalarray['condition']  = 'location';
            $finalarray['conditions'] = $geocondition;
            $final_json               = json_encode($finalarray);
        }

        if (json_decode($final_json) === null) {
            // invalid json comes from db, how come..
            throw new RuntimeException('Invalid json data while UrlConditions->josonizeUrlConditions');
        }

        return $final_json;
    }

    public function validateConditionsFinalURls($json_urlConditions): bool
    {
        $SmartyURL     = new SmartyUrl();
        $valid         = true;
        $urlConditions = json_decode($json_urlConditions);

        switch ($urlConditions->condition) {
            case 'location':
                foreach ($urlConditions->conditions as $geolocationcondition_url) {
                    if (! $SmartyURL->isValidURL($geolocationcondition_url)) {
                        // not valid url
                        return false;
                    }
                }
                break;

            case 'device':
                foreach ($urlConditions->conditions as $devicecondition_url) {
                    if (! $SmartyURL->isValidURL($devicecondition_url)) {
                        return false;
                    }
                }
                break;
        }

        return $valid;
    }
}
