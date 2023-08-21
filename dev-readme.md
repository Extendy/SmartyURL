Installation Instructions for developer
---------------------------------------


Installation
============

After you run
composer create-project extendy/smartyurl myapp

then do the following:

    $ cd myapp

    $ cp env .env
    and edit the file .env and make sure tou modify the database information

    To install shiled run	   
    $ php spark shield:setup
    or
    $ spark migrate --all
