# SmartyURL Developer's Guide

## Server Requirements

- You need a web hosting account (for a domain or sub-domain) with PHP 8.1 or higher support and the following PHP extensions (typically supported by most PHP hosting providers):

    - [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) , [intl](http://php.net/manual/en/intl.requirements.php) , [mbstring](http://php.net/manual/en/mbstring.installation.php) ,  [libcurl](https://www.php.net/manual/en/curl.setup.php) , [gmp](https://www.php.net/manual/en/gmp.installation.php) , [json](https://www.php.net/manual/en/json.installation.php), [bcmath](https://www.php.net/manual/en/bc.setup.php)

- Your web hosting account should have MySQL 8.0+ support

## Installation

Currently, as SmartyURL is in its early stages, you can only install it using `Composer`. Once we release the first official version of SmartyURL, we will offer detailed installation instructions for other methods.

### install SmartyURL  using `Composer`:

This is the best way for now.

Login to your web hosting account using SSH and run the following commands:

```cli
composer create-project extendy/smartyurl:dev-main myapp
cd myapp
composer install
cp env .env
```
When using `composer`, you can update SmartyURL dependencies in the future using `composer update`. However, for updating SmartyURL itself, manual updates or reinitializing the Composer project **are necessary**.

### After installing the files

Ensure that you've created a MySQL database, then proceed to edit the .env file. Update the database configuration and make any necessary changes to tailor the other settings to your specific requirements.

Next, execute the 'migrate' command to import the database structure into your created database using:

```cli
php spark migrate --all
```
Then you need to create the first user:

You can create a new user by running:

```cli
php spark shield:user create
```

or by visiting your website and register new user

Ensure the user you've created is designated as a superadmin by modifying the `auth_groups_users` database table. Set the user's group name to 'superadmin' instead of 'user' for the created user using phpMyAdmin or any mysql client and make sure the user status is active by set `active` to `1` in `users` tables. or you have to verify the email next time you login.

Afterward, you can disable new user registration by editing the .env file. Make sure to set `Auth.allowRegistration` to 'false'. If it's not already present in your .env file, you can add it like this:

```cli
Auth.allowRegistration = false
```

Afterward, you can access SmartyURL using any web browser by directing the URL to the installation domain where you have SmartyURL installed.

When logged in, you might be prompted to verify your email to activate your account. Please check your email for a verification link. If you are unable to access your email or SmartyURL unable to send emails, you can manually set the 'active' value to 1 in the 'users' database table for the user you've created.

## using `git`
During SmartyURL's development stage, it is not advisable for developers to attempt installation using git.

!!! note
If you planning to install SmartyURL with `git`, you gain the flexibility to easily update SmartyURL in the future using `git fetch` and `git pull`. However, **noting that** using `git` may provide bleeding-edge releases, which might not be as stable or thoroughly tested or may contains breaking changes.
Therefore Installing SmartyURL using `git` is recommended for Extendy Team developers only.


