# LocalPanel

A simple panel for your development Apache2 sites that you host on 127.0.0.0/8

### To install it:
```
> git clone https://github.com/Dawwa1/localpanel
> chmod -R 755 localpanel/ && chown -R www-data:www-data localpanel/ && mv localpanel /var/www
```
### Access
Now you can access the panel at http://localhost/localpanel! 

#### Hostname
If you want to access it by just the name, add a VirtualHost in `/etc/apache2/sites-enabled/localpanel.conf` and add the corresponding name and IP to `/etc/hosts`


## Errors
If you get any permission errors, make sure to add yourself to the `www-data` group with `usermod -aG www-data {YOUR USER}`
