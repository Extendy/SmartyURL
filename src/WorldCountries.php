<?php

namespace Extendy\Smartyurl;

use Sokil\IsoCodes\IsoCodesFactory;

class WorldCountries
{
    public function __construct()
    {
    }

    public function getCountriesList()
    {
        $isoCodes    = new IsoCodesFactory();
        $countries   = $isoCodes->getCountries();
        $countryList = [];

        foreach ($countries as $country) {
            $countryList[$country->getAlpha2()] = $country->getName();
        }

        return $countryList;
    }
}
