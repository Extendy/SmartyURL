# SmartyURL Developer Guide

## Installation

to install smartyurl:

```cli
composer create-project extendy/smartyurl myapp
cd myapp
composer update
cp env .env
```

make sure you create mysql database then run and edit `.env` file and change the configuration of database , also change the other configuration to be right for you.

then run the migrate all command:
```cli
spark migrate --all
```

As smarty still in test mode you can now visit the site and register an account using http://example.com/dashboard then register for an account and make it admin
in the future I will add more instructions..

