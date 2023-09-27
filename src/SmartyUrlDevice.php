<?php

namespace Extendy\Smartyurl;

use CodeIgniter\HTTP\UserAgent;
use Detection\Exception\MobileDetectException;
use Detection\MobileDetect;

class SmartyUrlDevice
{
    public function __construct()
    {
        $useragent             = new UserAgent();
        $userAgentString       = $useragent->getAgentString();
        $userAgentString       = ($userAgentString === '') ? 'Unknown' : $userAgentString;
        $this->userAgentString = $userAgentString;
    }

    /**
     * This function try to determine the operator (OS) of the visitor which may be
     * 'windows' , 'android' , 'iphone' , 'samsung' ... etc
     *
     * @param json $finalTargetURL_conditions
     */
    public function tryToKnowKnownOperator(): ?string
    {
        $return_string = null;
        $detect        = new MobileDetect();
        $detect->setUserAgent($this->userAgentString);
        // to the best to know the operator os

        // the OS will be in top
        if (preg_match('/Windows NT \d+\.\d+/', $this->userAgentString)) {
            $return_string = 'windows';
        }
        if (preg_match('/Linux/', $this->userAgentString)) {
            $return_string = 'linux';
        }

        // now the types will be below after OS to overwrite them
        // with return for each of then ... major first then the lowest priority
        if ($detect->isAndroidOS()) {
            return 'andriod';
        }
        if ($detect->isiPad() || $detect->isiPhone()) {
            return 'iphone';
        }

        if ($detect->isiPad() || $detect->isSamsung()) {
            return 'samsung';
        }

        // browsers
        if ($detect->isChrome()) {
            $return_string = 'chrome';
        }
        if ($detect->isFirefox()) {
            $return_string = 'firefox';
        }

        return $return_string;
    }

    /**
     * try to detect visitor device and return its name as string or null of device not detected.
     * return string can be
     * 'computer': for desktop and laptop
     * 'phone': for mobile phones
     * 'tablet': for tablets
     *  null: if not known device type
     *
     * @throws MobileDetectException
     */
    public function detectVistorDeviceType(): ?string
    {
        $visitor_device = null;
        $detect         = new MobileDetect();
        $detect->setUserAgent($this->userAgentString);

        return $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer';
    }
}
