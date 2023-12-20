# SmartyURL Developer's Guide

## Server Requirements

- You need a web hosting account (for a domain or sub-domain) with PHP 8.1 or higher support and the following PHP extensions (typically supported by most PHP hosting providers):

    - [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) , [intl](http://php.net/manual/en/intl.requirements.php) , [mbstring](http://php.net/manual/en/mbstring.installation.php) ,  [libcurl](https://www.php.net/manual/en/curl.setup.php) , [gmp](https://www.php.net/manual/en/gmp.installation.php) , [json](https://www.php.net/manual/en/json.installation.php), [bcmath](https://www.php.net/manual/en/bc.setup.php), [gmp](https://www.php.net/manual/en/gmp.setup.php)

- Your web hosting account should have MySQL 8.0+ support
- You need yo use `composer` and `php spark` command , therefor you must have SSH Access to your host account.
- Your hosting account should have `composer` command installed before.

## Prepare for installation

Before you begin the installation process for SmartyURL, it's crucial to set up the necessary environment. Follow these steps to ensure a smooth installation:

### Step 1: Choose a Subdomain

For optimal URL management using SmartyURL, it is recommended to create a new subdomain on your domain. For example, you can choose a subdomain like "go.example.com." This subdomain will serve as the base for your SmartyURL installation. Next any URL managed by SmartyURL will look like this (e.g https://go.example.com/link1)

### Step 2: Installation Location

While SmartyURL can be used on your apex domain (e.g., example.com), it is not the preferred or logical choice and does not make sense. For better organization and functionality, it's highly advised to use a separate subdomain. as descibed above.

#### Option 1: Subdomain Installation
Create a new subdomain, such as "go.example.com," and install SmartyURL on this subdomain.

#### Option 2: Subfolder Installation
If creating a subdomain is not possible, SmartyURL can be installed in a subfolder of your main domain. For instance, you can use a structure like "example.com/go/" where "go" becomes the root folder for SmartyURL.


## Installation

Currently, as SmartyURL is in its early stages, you can only install it using `Composer`. Once we release the first official version of SmartyURL, we may offer other installation methods.

### Install SmartyURL  using `Composer`:

This is the best way for now, to install SmartyURL you have to use `composer` command

Login to your web hosting account where you want to install SmartyURL using SSH and run the following commands:

```cli
composer create-project extendy/smartyurl:dev-main myapp
cd myapp
composer install
```
!!! note
    Your web site must be served from `myapp/public` , so choose the name `myapp` to fit with your hosting account configurations.

    The file `myapp/public/index.php` will be the main index page of your website. therefore consider where you will store the files in your web hosting account


### After installing the files

After getting SmartyURL files into your website first of all you have to create configuration file which called `.env`

there is A sample configuration file called `env` so you have to copy it to `.env` to let the configuration file take the affect by:

on the root folder of SmartyURL run the following command:

```bash
cp env .env
```

which will create a copy of the file `env` with new file name `.env`

The `.env` file now will be your SmartyURL configuration file, therefore you need to edit the file and make sure SmartyURL configuration is correct as you need.

### Creating MySQL Database:

Ensure that you've created a MySQL database (by using any tool that allow you to create a new mysql database and assign a user for the database like your hosting control panel or phpMyAdmin or command line).

Then proceed to edit the `.env` file. Update the database configuration in `# DATABASE` section and make any necessary changes to tailor the other settings to your specific requirements, including `app.baseUR` and `cookie.domain` and other options.

Next, execute the 'migrate' command to import the database structure into your created database.

while you are in the root SmartyURL project folder run the command:

```cli
php spark migrate --all
```

### Creating the first user:

You need to create the first user before you start using SmartyURL.

You can create a new user by running (create & activate) commands below :

```cli
php spark shield:user create
php spark shield:user activate
```
then add the user you have created to `superadmin` group by running:

```
php spark shield:user addgroup
```

**Rremember that**: we have 3 user groups by defalt `superadmin` and `admins` and `user` , see [SmartyURL Users](users.md/#user-groups) to know more about user groups.

When adding the user to a group and enter `superadmin' to set the user as super admin and choose user and enter username and confirm and the user will be added into user group

This is sample scenario:

```
$ php spark shield:user addgroup

CodeIgniter v4.4.3 Command Line Tool - Server Time: 2023-12-08 01:33:01 UTC+00:00


Group : superadmin
Add user to group by username or email ? [u, e]: u
Username : sam <---- this the username of the user you have created before
Add the user "sam" to the group "superadmin" ? [y, n]: y
User "sam" added to group "superadmin"
```

Afterward, you can access SmartyURL using any web browser by directing the URL to the installation domain where you have SmartyURL installed. (for example: https://go.example.com)

Remember, you can disable or enable new user registration by editing the `.env` file. Make sure to set `Auth.allowRegistration` to `false` to disable registration or `true` to enable registration. If it's not already present in your .env file, you can add it like this:

```cli
Auth.allowRegistration = false
```

By default user registration using the portal is **disabled** because SmartyURL is Closed by Default. See [Why SmartyURL is Closed by Default](users.md/#why-smartyurl-is-closed-by-default) to know why.

## Using `git` for SmartyUrl developers only
While it is not advisable for developers to attempt installation using git. but sure you can do that. you need some experice usng git to install code and run the commands in [After installing the files](developers.md/#after-installing-the-files)

!!! note
    If you planning to install SmartyURL with `git`, you gain the flexibility to easily update SmartyURL in the future using `git fetch` and `git pull`. However, **noting that** using `git` may provide bleeding-edge releases, which might not be as stable or thoroughly tested or may contains breaking changes.
    Therefore Installing SmartyURL using `git` is recommended for Extendy Team developers only.


