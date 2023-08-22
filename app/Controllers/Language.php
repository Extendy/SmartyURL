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
        //@TODO we must think if we need to redirect the visitor to the previous url instead of base url
        $url = base_url();
        return redirect()->to($url);

    }

}