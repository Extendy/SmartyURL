# SmartyURL Developer Guide

## Installation

Currently, as SmartyURL is in its early stages, you can only install it using Composer. Once we release the first official version of SmartyURL, we will offer detailed installation instructions for other methods.

install SmartyURL using composer:

```cli
composer create-project extendy/smartyurl myapp
cd myapp
composer install
cp env .env
```

Ensure that you've created a MySQL database, then proceed to edit the .env file. Update the database configuration and make any necessary changes to tailor the other settings to your specific requirements.

then run the migrate all command to import database:

```cli
php spark migrate --all
```
Then you need to create the first user:

You can create a new user by running:

```cli
php spark shield:user create
```

or by visiting your website and register new user

Ensure the user you've created is designated as a superadmin by modifying the `auth_groups_users` database table. Set the user's group name to 'superadmin' instead of 'user' for the created user.

Afterward, you can disable new user registration by editing the .env file. Make sure to set `Auth.allowRegistration` to 'false'. If it's not already present in your .env file, you can add it like this:

```cli
Auth.allowRegistration = false
```
