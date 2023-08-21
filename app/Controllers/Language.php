<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Authentication\Authenticators;

class Language extends BaseController
{
    public function index()
    {
        $session = session();
        $locale = $this->request->getLocale();
        $session->remove('lang');
        $session->set('lang', $locale);
        $url = base_url();
        return redirect()->to($url);

    }

}