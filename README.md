# LocalPanel

A simple panel for your development Apache2 sites that you host on 127.0.0.0/8

### To install it:
```
git clone https://github.com/Dawwa1/localpanel
chmod -R 775 localpanel/ && chown -R www-data:www-data localpanel/ && mv localpanel /var/www
```

### How to use it:

#### First, add the following line to `sudo visudo`
```
{apache_user} ALL=(ALL) NOPASSWD: /bin/bash /var/www/localpanel/create_vhost
```
`{apache_user}` should be the user that Apache uses.
- On **Debian**-based distro's, it will be `www-data`
- On **Fedora**-based distro's, it will be `apache`

---

`index.php` automatically scrapes `/var/www` for the list, directing you to the folder (will add database scraping later).
When you create a virtual host (bottom right button), `create_vhost` also now creates an Apache config file as well as taking an argument for a domain in `/etc/hosts`!

### Access
Add a VirtualHost in your apache config, and now you can access the panel @ `http://localhost/localpanel`!
You can also add the IP and a domain to `/etc/hosts` to access it @, for example, `http://localhost.dd`


## Errors
If you get any permission errors, make sure to add yourself to the `www-data` group with `usermod -aG www-data {YOUR USER}`
