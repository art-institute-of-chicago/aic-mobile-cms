![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# Mobile CMS
> This repository contains the code that manages content for Art Institute of Chicago mobile app.

## Installing

For local development, we run the CMS in a [Homestead](https://laravel.com/docs/master/homestead) environment which provides all the software required to run the website. To set up the project, set up a basic Homestead environment locally then do the following:

1. Add a `folders.map` value in `Homestead.yaml` with the path to this directory:
  ```
  - map: "~/Documents/aic-mobile-cms"
    to: /home/vagrant/aic-mobile-cms
  ```
2. Optionally, also add the data-hub-foundation to `folders.map` if you need to do work on that project simultaneously:
  ```
  - map: "~/Documents/data-hub-foundation"
    to: /home/vagrant/data-hub-foundation
  ```
3. Add an entry to `sites.map` in `Homestead.yaml` to access your site locally:
  ```
  - map: mobile-dev.artic.edu
    to: "/home/vagrant/aic-mobile-cms"
    php: '8.1'
  ```
4. Add a `mobile-cms` value to the `databases` array in `Homestead.yaml`
5. Add the domain you defined in `Homestead.yaml` to your local `/etc/hosts` file
6. Run `vagrant up` to provision your vagrant machine, or `vagrant provision` if you're changing an already-existing environment
7. Run `vagrant ssh` to login to the VM
8. Navigate to the project directory and run `composer install` to install the PHP dependencies
9. Copy `.env.example` as `.env` and update with your local settings
10. Run `php artisan key:generate` to generate your application key
11. Run `php artisan migrate` to migrate the database schema
12. Run `php artisan twill:superadmin` to create a superadmin user
13. Build all necessary Twill assets: `php artisan twill:build`
14. Access the frontend at http://{your_dev_domain}.
15. Access the CMS at http://{your_dev_domain}/admin.
