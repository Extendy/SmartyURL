<?php

namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Localization implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        
        $session = session();
        $language = \Config\Services::language();
        //detect if there is a cookie smarty_layout_lang stores
        //if no cookie, i will check session for layout lang
        //in no session for layout lanf i will use the co default locale

        //Note that @TODO @FIXME we must update the BaseController.php file also

        //i will check the session to see if there is lang store
        $session_smarty_layout_lang = $session->get('lang');
        if ($session_smarty_layout_lang != null){
            //i will set the lang for the layout and return
            $language->setLocale($session->lang);
            return;
        }

        //i will try to get the cookie smarty_lang_layout to see if there is a stored value

        $cookie_smarty_lang_layout = get_cookie(esc("smarty_lang_layout"));
        if ($cookie_smarty_lang_layout != null){
            //i will check if cookie value is valid lang i will set it and return
            $language->setLocale($cookie_smarty_lang_layout);
            //and i will set the session
            $session->set("lang",$cookie_smarty_lang_layout);
            return;
        }

        //if we are here so
        //we will use the default system lang as layout language
        //becaise none of the above conditions are true , so language not known until now
        //that mean this is may be the first call of the system at this session

        $context = 'user:' . user_id();
        $usercustomlocale = setting()->get('App.defaultLocale', $context);
        if ($usercustomlocale == ""){
            //no custom lang for user , use def
            $language->setLocale(setting("App.defaultLocale"));
        } else {
            //there is cusom locale for user
            $language->setLocale($usercustomlocale);
        }
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }

}