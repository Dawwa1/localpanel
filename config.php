<?php
// Configuration file for LocalPanel

// for when shit goes sideways
const DEBUG = true;

// credentials of a MySQL admin account
// used for creating databases for sites
const ADMIN_DB_USER = "admin";
const ADMIN_DB_PASS = "1122";

// login details for MySQL
// Default: localhost:locpan:locpan:locpan
const MAIN_DB = [
    "host" => "localhost",
    "dbname" => "locpan",
    "user" => "locpan",
    "pass" => "locpan"
];

// full path to web server directories *HAS TO HAVE TRAILING SLASH
// Default: /var/www
const WEB_DIR_PATH = "/var/www/";

// url of your local DB
// Default: http://localhost/phpmyadmin/
const DB_URL = "http://localhost/phpmyadmin/";

?>