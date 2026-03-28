# LocalPanel

A simple panel for your development Apache2 sites that you host on 127.0.0.0/8

### To install it:
#### Firstly, clone the repository and set it up in `/var/www`.
```
git clone https://github.com/Dawwa1/localpanel
chmod -R 775 localpanel/ && chown -R www-data:www-data localpanel/ && mv localpanel /var/www
```
#### Second, make sure you have the following configured and running:
- **PHP 8.4** *(PHP 5.4+ should work too)*
- **PHPMyAdmin**
- **MariaDB 10.11+**
---
### How to use it:

#### First, add the following line to `sudo visudo`
*This will allow the app to call `create_vhost` as sudo to create virtual hosts*
```
{apache_user} ALL=(ALL) NOPASSWD: /bin/bash /var/www/localpanel/create_vhost
```
`{apache_user}` should be the user that Apache uses.
- On **Debian**-based distro's, it will be `www-data`
- On **Fedora**-based distro's, it will be `apache` <br>

#### Second, set up the database
- On line **7** and **8**, change the credentials to a MariaDB admin user <br>
- Run `create.sql` through either the command line or PHPMyAdmin

### What it does
`index.php` automatically scrapes `/var/www` for untracked files (files that aren't saved in the DB), and searches the database for tracked ones. <br> <br>
When you create a virtual host (bottom right button), `create_vhost` also now creates an Apache config file as well as taking an argument for a domain in `/etc/hosts`! <br>
You can right click on untracked sites *(red border)* to track them, or right click on any of them to hide them.

### Access
Add a VirtualHost in your apache config, and now you can access the panel @ http://localhost/localpanel!
You can also add the IP and a domain to `/etc/hosts` to access it @, for example, `http://localhost.dd`


## Errors
If you get any permission errors, make sure to add yourself to the `www-data` group with `usermod -aG www-data {YOUR USER}`
