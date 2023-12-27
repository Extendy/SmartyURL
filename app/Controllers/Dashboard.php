<?php

namespace App\Controllers;

use App\Models\UrlHitsModel;
use App\Models\UrlModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [];
        // i will get all urls count
        $urlmodel               = new UrlModel();
        $urlhitsmodel           = new UrlHitsModel();
        $show_global_statistics = false;

        switch (setting('Smartyurl.show_global_statistics_in_dashboard')) {
            case 'all':
                $show_global_statistics = true;
                break;

            case 'permitted':
                if (auth()->user()->can('super.admin', 'admin.manageotherurls')) {
                    $show_global_statistics = true;
                }
                break;

            case 'none':
                $show_global_statistics = false;
                break;
        }
        $data['show_global_statistics'] = $show_global_statistics;

        // All URLS statistics
        if ($show_global_statistics) {
            $all_urls_count         = $urlmodel->getUrlCount();
            $data['all_urls_count'] = $all_urls_count;

            // this month added urls
            $all_urls_this_month         = $urlmodel->getUrlCount(null, 'this_month');
            $data['all_urls_this_month'] = $all_urls_this_month;

            // rodat added all urls
            $all_urls_today         = $urlmodel->getUrlCount(null, 'today');
            $data['all_urls_today'] = $all_urls_today;

            // working with hits statistics

            // all hits
            $all_hits_count         = $urlhitsmodel->getCountHits();
            $data['all_hits_count'] = $all_hits_count;

            // all hits this month

            $all_hits_this_month         = $urlhitsmodel->getCountHits(null, 'this_month');
            $data['all_hits_this_month'] = $all_hits_this_month;

            // all hits todat
            $all_hits_today         = $urlhitsmodel->getCountHits(null, 'today');
            $data['all_hits_today'] = $all_hits_today;
        }

        // Logged in user statistics (My URL statistics)

        // all hits for my urls all time
        $myurl_hits_alltime         = $urlhitsmodel->getCountHits(user_id());
        $data['myurl_hits_alltime'] = $myurl_hits_alltime;

        // all hits for my urls this month
        $myurl_hits_thismonth         = $urlhitsmodel->getCountHits(user_id(), 'this_month');
        $data['myurl_hits_thismonth'] = $myurl_hits_thismonth;

        // all hits for my urls today
        $myurl_hits_today         = $urlhitsmodel->getCountHits(user_id(), 'today');
        $data['myurl_hits_today'] = $myurl_hits_today;

        return view(smarty_view('dashboard'), $data);
    }
}
