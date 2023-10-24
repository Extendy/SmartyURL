# IP2Location Database

SmartyURL utilizes the `ip2location/ip2location-php` library to determine the country of visitors based on their IP addresses. This functionality relies on the IP2Location database provided by ip2location.com. Since SmartyURL is open-source and freely distributed, we include the "IP2Location™ LITE IP-COUNTRY Database," which is free for both personal and commercial use.

**It's important to note that** while the IP2Location™ LITE IP-COUNTRY Database is a valuable resource, it's essential to be aware that it may not consistently offer the highest degree of accuracy or the most up-to-date information. If you have plans to extensively employ geographical redirect conditions and demand a more precise and current dataset for IP-based country information, we strongly advise considering the purchase of a licensed version of the IP2Location database from ip2location.com. This paid version ensures you have access to the most accurate and regularly updated data for your geographical redirection needs.

The paid IP2Location database offers improved accuracy and regular updates. To obtain your license and database, please visit [ip2location.com/database/ip2location](https://www.ip2location.com/database/ip2location).

Once you've obtained the IP2Location™ IP-Country Database, download the .BIN file associated with the database and upload it to your server. Then, specify the path to the uploaded file by configuring the `$ip2location_bin_file` in `app/Config/Smartyurl.php`.

```php
public $ip2location_bin_file = VENDORPATH . 'ip2location/ip2location-php/data/IP2LOCATION-LITE-DB1.BIN';
```

This setup ensures that SmartyURL utilizes the paid, more accurate, and regularly updated IP2Location database for enhanced country detection.

Or Alternatively, you can continue using the included Free LITE version.
