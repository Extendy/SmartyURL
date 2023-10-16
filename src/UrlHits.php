<?php

namespace Extendy\Smartyurl;

use App\Models\UrlHitsModel;
use App\Models\UrlModel;
use CodeIgniter\I18n\Time;

class UrlHits extends SmartyUrlDevice
{
    public function __construct()
    {
        parent::__construct();
        $request       = service('request');
        $this->request = $request;
    }

    /**
     * Record A hit for URL
     */
    public function recordUrlHit(int $urlId, array $anyCollectedData = []): bool
    {
        $hit_data = [];
        $urlmodel = new UrlModel();
        // first of all make sure from $urlId is exists in db or return false
        $urlData = $urlmodel->where('url_id', $urlId)->first();
        if ($urlData === null) {
            return false;
        }
        $hit_data['urlhit_urlid'] = $urlId;
        // try to know access time
        $time                  = new Time('now');
        $hit_data['urlhit_at'] = $time;
        // try to know visitor country
        $visitorCountry             = $anyCollectedData['visitorCountry'] ?? smarty_get_visitor_ip_country();
        $hit_data['urlhit_country'] = $visitorCountry;
        // try to know the visitor IP
        $hit_data['urlhit_ip'] = $this->request->getIPAddress();
        // try to know what was the final redirect url
        $finalUrl                       = $anyCollectedData['finalUrl'] ?? $urlData['url_targeturl'];
        $hit_data['urlhit_finaltarget'] = $finalUrl;
        // know the user agent
        $hit_data['urlhit_useragent'] = $this->userAgentString;

        $urlhitsmodel                     = new UrlHitsModel();
        $hit_data['urlhit_visitordevice'] = $this->detectVistorDeviceType();

        $inserted = $urlhitsmodel->insert($hit_data);
        if ($inserted) {
            $insertedHitId = $urlhitsmodel->insertID();
            // i will increase the hits counter
            $urlmodel->increaseHitsCount($urlId);

            return true;
        }

        return false;
    }
}
