<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $cookie_smarty_lang_layout = get_cookie(esc('smarty_lang_layout'), true);
        d($cookie_smarty_lang_layout);

        return view('welcome_message');
    }
}
