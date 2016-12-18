Berny\Project-Manager
=====================

Host multiple projects in your localhost and access them with urls like `http://project-name.devel/...`

Download
--------
Use one of the following methods:
- [git](https://git-scm.com): run `git clone https://github.com/xphere/project-manager`
- [github](https://github.com/xphere/project-manager): download and extract [this file](https://github.com/xphere/project-manager/archive/master.zip)

Except in the first case, you need to execute `composer install`

Installation
------------
1. Move the content of this project to /var/www
2. Run `composer install`. You must have [composer](https://getcomposer.org/download/) installed.
3. Copy `config/devel.vhost` to your apache sites directory, often `/etc/apache2/sites-available/`
4. Copy `config/devel.tld` to your dnsmasq config directory, usually `/etc/dnsmasq.d/`
5. Restart apache and dnsmasq services
6. Optionally, you can add `/var/www/bin` to your `$PATH` so you don't need to write the full path every time.

        $> export PATH=$PATH:/var/www/bin
    Add the line below to your `~/.profile` if you want the change to be permanent.

Usage
-----

For the commands above I'm going to asume you've added `/var/www/bin` to your `$PATH`.

To get help from the command line, run:

    $> project-manager

Start managing a project running this command:

    $> project-manager add
Then follow the instructions of the console.
I suggest to run the command from the public directory of your project.

You can enable a managed project later with:

    $> project-manager enable project-name

Now you're ready to browse to `http://project-name.devel`
Don't forget to restart your webserver after enabling virtual hosts.

You can disable an active project with the next call:

    $> project-manager disable project-name

Or remove it from project management with:

    $> project-manager remove project-name

Also, you can list all projects managed calling this command:

    $> project-manager project:list
    $> project-manager project:list --type=enabled  # Only enabled projects
    $> project-manager project:list --type=disabled # Only disabled projects

Easy, isn't it? ;)
