<?php

namespace App\Controllers;

class Language extends BaseController
{
    public function index()
    {
        $session  = session();
        $response = service('response');
        helper('cookie');

        // git the request
        $locale = $this->request->getLocale();
        // @TODO i must check the request lang is supported language or not
        $session->remove('lang');
        $session->set('lang', $locale);
        // because the user ask /ar/ request so he is interested on this lang so
        // i will set the cookie smarty_lang_layout to store this for user
        set_cookie([
            'name'   => 'smarty_lang_layout',
            'value'  => $locale,
            'expire' => 3600,
        ]);
        // @TODO set_cookie not working i don't know why so i use setcookie php command insted until this is fixed
        // set_cookie('smarty_lang_layout', $locale);
        setcookie('smarty_lang_layout', $locale, time() + 2592000, '/', setting('Cookie.domain'), false, false);

        $jumpurl = request()->getVar('jump');
        if (isset($jumpurl)) {
            $url = site_url($jumpurl);
        } else {
            $url = setting('Auth.redirects')['login'];
        }

        return redirect()->to($url);
    }
}
