# SmartyURL

> [!IMPORTANT]
> **NOT READY!! The application is currently under development and not yet ready for use. We will remove this notice once it becomes available for use.**


SmartyURL, a powerful & open-source URL management tool, empowers businesses and individuals they need self-hosted tool to customize, track, shorten URLs, and manage their URLs for marketing, analytics, and reporting.

You can utilize SmartyURL to generate redirect links to the final URL, considering various variables like the visitor's geographical location or user device information.


![image](https://github.com/extendy-sam/SmartyURL/assets/146824708/3f24ac02-d42e-413f-a2d8-5564587862f1)


For instance, you can create a unified link for mobile app downloads, dynamically adjusting the destination URL based on the user's device, whether it runs on Android or iOS. This ensures users are directed to the appropriate download link from the official store, tailored to their operating system.

![image](https://github.com/extendy-sam/SmartyURL/assets/146824708/22b62a10-e02c-43e7-8d76-2f875f6d9230)


Likewise, you can also generate intelligent links to route visitors to specific URLs based on their location. For instance, users from the United States will be redirected to one link, while those from Saudi Arabia will be directed to another

![image](https://github.com/extendy-sam/SmartyURL/assets/146824708/0afe4a88-918e-4713-bab7-fe0d51d08433)


In addition to shortening URLs (based on domain that you use), SmartyURL also offers a variety of other features, such as:

* **Space-saving and improved readability**
* **Smart URL redirects.**
* **Tracking and analytics**
* **Customization**

## Server Requirements

- You need a web hosting account (for a domain or sub-domain) with PHP 7.4 or higher support and the following PHP extensions (typically supported by most PHP hosting providers):

  - [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) , [intl](http://php.net/manual/en/intl.requirements.php) , [mbstring](http://php.net/manual/en/mbstring.installation.php) ,  [libcurl](https://www.php.net/manual/en/curl.setup.php) , [gmp](https://www.php.net/manual/en/gmp.installation.php) , [json](https://www.php.net/manual/en/json.installation.php), [bcmath](https://www.php.net/manual/en/bc.setup.php)
 
- Your web hosting account should have MySQL 8.0+ support

Afterwards, you can refer to the [installation instructions](_docs/installation.md) to set up the tool on your hosting account and begin using it.

Certainly, please refer to the [documentation](_docs/index.md) for detailed instructions on how to install, configure, and effectively use Smart URL for comprehensive guidance.

**Visitors IP Country detection**

SmartyURL uses the `ip2location/ip2location-php` library to determine visitors country based on their IP addresses. It includes the free "IP2Location™ LITE IP-COUNTRY Database" for both personal and commercial use. For enhanced geographical redirect conditions with more accurate and up-to-date IP-based country data or if you need more accuracy consider purchasing a licensed IP2Location database. Refer to [IP2Location Database Docs](_docs/ip2location.md) for more details.


## Documentation

Please take a look to SmartyURL [documentation](_docs/index.md) for detailed installation, configuration, and usage instructions.


## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

Every open-source project depends on its contributors to be a success. Thanks to:

<a href="https://github.com/extendy/smartyurl/graphs/contributors">
<img src="https://contrib.rocks/image?repo=extendy/smartyurl" />
</a>

Also We would like to acknowledge the following resources and contributors for their valuable assistance and support in the development of this project:

- [MassarCloud Company](https://massarcloud.sa): We would like to express our gratitude to [MassarCloud LLC](https://massarcloud.sa) for their valuable support in providing hosting services during the development of this project.

## SmartyURL Legal Notice

For more information, please refer to the [Legal Notice](_docs/legalnotice.md).
