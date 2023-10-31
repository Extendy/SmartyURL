# IP2Location Database

SmartyURL utilizes the `ip2location/ip2location-php` library to determine the country of visitors based on their IP addresses. This functionality relies on the IP2Location database provided by IP2Location.com. Since SmartyURL is open-source and freely distributed, we include the "IP2Location™ LITE IP-COUNTRY Database," which is free for both personal and commercial use.

**It's important to note that** while the IP2Location™ LITE IP-COUNTRY Database is a valuable resource, it's essential to be aware that it may not consistently offer the highest degree of accuracy or the most up-to-date information. If you have plans to extensively employ geographical redirect conditions and demand a more precise and current dataset for IP-based country information, we strongly advise considering the purchase of a licensed version of the IP2Location database from ip2location.com. This paid version ensures you have access to the most accurate and regularly updated data for your geographical redirection needs.

**Alternatively, you can continue with the included free LITE version, although it's not fully accurate, it can still be helpful.**

**If you want to use the paid version of IP2Location database:**

The paid IP2Location database offers improved accuracy and regular updates. To obtain your license and database, please visit [ip2location.com/database/ip2location](https://www.ip2location.com/database/ip2location).

Once you've obtained the IP2Location™ IP-Country Database, download the .BIN file associated with the database and upload it to your server. Then, specify the path to the uploaded file by configuring the `$ip2location_bin_file` in `app/Config/Smartyurl.php`.

```php
public $ip2location_bin_file = VENDORPATH . 'ip2location/ip2location-php/data/IP2LOCATION-LITE-DB1.BIN';
```

The uploaded BIN file should be the standard version provided by IP2Location.

> [!IMPORTANT]
> When uploading your custom IP2Location BIN file, please refrain from overwriting the default 'IP2LOCATION-LITE-DB1.BIN' file. Instead, upload it with a new filename and adjust the `ip2location_bin_file` setting accordingly. This will ensures that your file won't be overwritten when SmartyURL is updated to a new version in the future.

This setup ensures that SmartyURL uses the paid, more accurate, and regularly updated IP2Location database for enhanced country detection.

**Please note that:**  When using the paid version of IP2Location, please remember to manually update the database (.BIN) file regularly whenever IP2Location announces a database update. This process is not handled automaticlly by SmartyURL, so it's important to stay informed about IP2Location announcements.



