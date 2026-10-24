<?php
// Configuration file for LocalPanel

// for when shit goes sideways
const DEBUG = true;

// login details for MySQL
// Must be a user with full privileges to the database
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