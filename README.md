Berny\Project-Manager
=====================

Host multiple projects in your localhost and access them with urls like `http://project-name.devel/...`

Installation
------------

********
**NOTE** This project is under heavy redesign, porting from bash to php
********

1. Move the content of this project to /var/www
2. Copy `config/devel.vhost` to your apache sites directory, often `/etc/apache2/sites-available/`
3. Copy `config/devel.tld` to your dnsmasq config directory, usually `/etc/dnsmasq.d/`
4. Restart apache and dnsmasq services

Usage
-----

Under the public directory of your project, run this script:

    $> /var/www/bin/project-add $(pwd) project-name

Then active your project from anywhere with:

    $> /var/www/bin/project-enable project-name

Optionally, you can add `/var/www/bin` to your `$PATH` so you don't need to write the full path every time.

    $> PATH=$PATH:/var/www/bin
