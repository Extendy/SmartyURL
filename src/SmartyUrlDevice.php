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
     * This function verifies the visitor device and checks if it equals the given device type and returns the finalurl if yes
     *
     * @param mixed $devicetype string like "andriodsmartphone" "applesmartphone" "windowscomputer"
     * @param mixed $finalurl   string if the finalurl for that device
     *
     * @return mixed|void
     *
     * @throws MobileDetectException
     */
    public function verifyDeviceCondition($devicetype, $finalurl): null|string
    {
        $visitorOperator   = $this->tryToKnowKnownOperator();
        $visitorDeviceType = $this->detectVistorDeviceType();
        // i will create the visitor device string
        $visitorDeviceString = '';

        // the operator
        switch ($visitorOperator) {
            case 'windows':
                $visitorDeviceString .= 'windows';
                break;

            case 'linux':
                $visitorDeviceString .= 'linux';
                break;

            case 'andriod':
                $visitorDeviceString .= 'andriod';
                break;

            case 'iphone':
                $visitorDeviceString .= 'apple';
                break;

            case 'mac':
                $visitorDeviceString .= 'mac';
                break;

            default:
                $visitorDeviceString .= '';
                break;
        }

        // the device type
        switch ($visitorDeviceType) {
            case 'computer':
                $visitorDeviceString .= 'computer';
                break;

            case 'tablet':
                $visitorDeviceString .= 'smartphone';
                break;

            case 'phone':
                $visitorDeviceString .= 'smartphone';
                break;

            default:
                $visitorDeviceString .= '';
                break;
        }

        // i will compare if $devicetype equals $visitorDeviceString this i will return the finalurl
        if ($devicetype === $visitorDeviceString) {
            return $finalurl;
        }

        return null;
    }

    /**
     * This function try to determine the operator (OS) of the visitor which may be
     * 'windows' , 'android' , 'iphone' , 'samsung' ... etc
     *
     * @param json $finalTargetURL_conditions
     */
    private function tryToKnowKnownOperator(): ?string
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
        if (preg_match('/Macintosh/', $this->userAgentString)) {
            $return_string = 'mac';
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
    private function detectVistorDeviceType(): ?string
    {
        $visitor_device = null;
        $detect         = new MobileDetect();
        $detect->setUserAgent($this->userAgentString);

        return $detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer';
    }
}
