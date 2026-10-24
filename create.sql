CREATE USER 'locpan'@'localhost' IDENTIFIED VIA mysql_native_password USING 'locpan';
GRANT ALL PRIVILEGES ON *.* TO 'locpan'@'localhost' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
CREATE DATABASE IF NOT EXISTS `locpan`;GRANT ALL PRIVILEGES ON `locpan`.* TO 'locpan'@'localhost';

CREATE TABLE `sites` (
                             `name` varchar(32) DEFAULT NULL,
                             `ip` varchar(16) DEFAULT NULL,
                             `dom` varchar(32) DEFAULT NULL,
                             `hidden` tinyint(1) NOT NULL DEFAULT 1,
                             `root_path` tinytext DEFAULT NULL,
                             `conf_path` tinytext DEFAULT NULL,
                             `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci