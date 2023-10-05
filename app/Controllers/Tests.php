<?php
/**
 * This Controller is for tests only
 * it is not a part of real production application.
 */

namespace App\Controllers;

use IP2Location\IpTools;
use Locale;
use Sokil\IsoCodes\IsoCodesFactory;
use Sokil\IsoCodes\TranslationDriver\GettextExtensionDriver;

/**
 * Class BaseController
 *
 * Url Controller Deal with URL of SmartyURL
 *
 * For security be sure to declare any new methods as protected or private.
 */
class Tests extends BaseController
{
    public function index()
    {
        $ipTools = new IPTools();

        // Call the getVisitorIp() method on the instance
        $visitorIp = $ipTools->getVisitorIp();
        echo $visitorIp;
        d('this is the index methos of tests controller');
    }

    public function testCountry()
    {
        /*
         // define locale
          putenv('LANGUAGE=ar_JO.utf8');
          putenv('LC_ALL=ar_JO.utf8');
          setlocale(LC_ALL, 'ar_JO.utf8');

          // init database
          $isoCodes = new IsoCodesFactory();

          // get languages database
          $languages = $isoCodes->getLanguages();

          // get local name of language
          echo $languages->getByAlpha2('ar')->getLocalName(); // will print 'українська'
          echo "<hr>";

          //////SSS
          //echo Locale::getDefault();
          echo env('LC_ALL');
          die;

          $foo = new GettextExtensionDriver();
          $foo->setLocale("ar_JO.utf8");



          $isoCodes = new IsoCodesFactory(null, $foo);

          $languages = $isoCodes->getLanguages();
          echo $languages->getByAlpha2('ar')->getLocalName();
          dd($languages);

          $countries = $isoCodes->getCountries();
          //dd($countries->getByAlpha2('UA'));
        */

        $isoCodes  = new IsoCodesFactory();
        $countries = $isoCodes->getCountries();

        foreach ($countries as $country) {
            // dd($country);
            echo '<hr>' . $country->getAlpha2() . '=>' . $country->getName();
        }
    }
}
