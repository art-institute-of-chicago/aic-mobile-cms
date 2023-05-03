![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

Mobile CMS
-----
This repository contains the code that manages content for the mobile app.

## Installing

### Homestead

For local development, we run the CMS in a [Homestead](https://laravel.com/docs/master/homestead) environment which provides all the software required to run the website.

* Update the `folders.map` in `Homestead.yaml` with the path to this directory:
  ```
  - map: "~/Documents/aic-mobile-cms"
    to: /home/vagrant/aic-mobile-cms
  ```
* Optionally, also add the data-hub-foundation to `folders.map` if you need to do work on that project simultaneously:
  ```
  - map: "~/Documents/data-hub-foundation"
    to: /home/vagrant/data-hub-foundation
  ```
* Add an entry to `sites.map` in `Homestead.yaml` to access your site locally:
  ```
  - map: mobile-cms-dev.artic.edu
    to: "/home/vagrant/aic-mobile-cms"
    php: '8.1'
  ```
* Add the domain your defined in `Homestead.yaml` to your local `/etc/hosts` file
* Run `vagrant up` to provision your vagrant machine, or `vagrant provision` if you're changing to an already-existing environment
* Run `vagrant ssh` to login to the VM
* Navigate to the project directory and run `composer install` to install the
PHP dependencies
* Copy `.env.example` as `.env` and update with your local settings
* Run `php artisan key:generate` to generate your application key
* Run `php artisan migrate` to migrate the database schema
* Run `php artisan twill:superadmin` to create a superadmin user
* Build all necessary Twill assets: `php artisan twill:build`
* Access the frontend at http://{your_dev_domain}.
* Access the CMS at http://admin.{your_dev_domain}.
