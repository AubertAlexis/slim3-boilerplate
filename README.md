# Slim3 Boilerplate

This slim3 boilerplate is made for everyone who would like to kickstart quickly a web project.
`PHP ^7.0` required.
It is composed of many great packages such as:

  - Eloquent
  - Phinx
  - Monolog
  - Symfony Console
  - Bootstrap 3 (should be updated to version 4 soon)
  - JQuery
  - leafo/scssphp
  - matthiasmullie/minify


## Guide

### Assets management

`php manager assets:compile` : 
Will take care of converting your `scss` files and minify them as well as `js` files from `/assets` directory
to respectively `/public/css` and `/public/js` directories 
(no binary dependencies required).

### Migration creation

`php manager migration:create <migration_name>` :
Will automatically creates a new migration file to `/db/migrations`
from this file you can follow Eloquent documentation to describe your migration.
https://laravel.com/docs/5.4/migrations#migration-structure

### Note
Feel free to open issues, ask questions or make some pull requests ! I'm maintaining this on my spare time so give me some time to get back to you :)

Enjoy !
