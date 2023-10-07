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
            // this is a device condition
            $device          = $conditions['device'];
            $devicefinalurl  = $conditions['devicefinalurl'];
            $devicecondition = [];

            for ($i = 0; $i < count($device); $i++) {
                switch ($device[$i]) {
                    case 'windowscomputer':
                        $devicecondition[]['computer'][] = ['windows' => $devicefinalurl[$i]];
                        break;

                    case 'applesmartphone':
                        $devicecondition[]['smartphone'][] = ['iphone' => $devicefinalurl[$i]];
                        break;

                    case 'andriodsmartphone':
                        $devicecondition[]['smartphone'][] = ['andriod' => $devicefinalurl[$i]];
                        break;

                    default:
                        break;
                }
            }
            $finalarray               = [];
            $finalarray['condition']  = 'device';
            $finalarray['conditions'] = $devicecondition;
            $final_json               = json_encode($finalarray);
        }

        // geolocation
        if ($conditions['geocountry'] !== null) {
            // this is geocountry condition
            $geocountry   = $conditions['geocountry'];
            $geofinalurl  = $conditions['geofinalurl'];
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
}
