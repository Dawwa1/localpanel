# LocalPanel

LocalPanel is a web-based application for doing some simple management of Apache virtual hosts,
written completely in PHP. It is intended for local development and testing, **please do not expose it to the public internet**.

## Project structure

```text
localpanel/
├── bin/
│   └── localpanel.php          # executable script for managing virtual hosts and /etc/hosts entries
├── src/
│   ├── Controllers/             # HTTP request handlers
│   ├── Models/                  # Database and site models
│   ├── Views/
│   │   ├── assets/              # Images and other frontend assets
│   │   ├── pages/               # Full page templates
│   │   ├── partials/            # Shared page sections
│   │   └── layout.php           # Shared HTML layout
│   ├── View.php                 # View rendering
├── /vendor, composer.*          # Composer dependencies, do not edit
├── config.php                   # Local installation configuration
├── create.sql                   # Database schema
├── index.php                    # Application entry point
├── composer.json                # PHP dependencies
└── README.md                    # You're reading it!a
```

## Requirements

- Linux
- Apache
- PHP with the extensions required by the project
- MySQL
- Composer
- Root or `sudo` access during installation

## Installation

```bash
git clone https://github.com/Dawwa1/localpanel.git
cd localpanel
python3 installer.py
```

The installer will:

1. Verify that MySQL, PHP, and Apache are installed, installing missing
   packages when supported by the host system.
2. Ask for the database and application settings required for the first
   launch.
3. Generate `config.php` from those answers.
4. Add the LocalPanel command to `sudoers` through `visudo`, allowing the
   Apache user to execute only the required `/bin/localpanel` command.
5. Check the resulting installation and display the remaining setup steps.

The installer will not silently overwrite an existing `config.php` or
sudoers configuration. Review its proposed changes before confirming them.

## Database setup

After installation, create the LocalPanel database schema by running
`create.sql` with the MySQL account configured in `config.php`:

```bash
mysql -u YOUR_DB_USER -p YOUR_DATABASE < create.sql
```

The installer can configure the connection values, but it will not
automatically execute `create.sql`. You must run this step before the
application can read or create site records.

## `/bin/localpanel`

The privileged command is located at `bin/localpanel.php`. It creates,
updates, and deletes Apache virtual-host configurations and synchronizes
entries in `/etc/hosts`.

Typical usage:

```bash
sudo /var/www/localpanel/bin/localpanel create SITE_NAME IP_ADDRESS DOMAIN
sudo /var/www/localpanel/bin/localpanel update SITE_NAME IP_ADDRESS DOMAIN
sudo /var/www/localpanel/bin/localpanel delete SITE_NAME
```

An optional document root can be supplied for `create` and `update`:

```bash
sudo /var/www/localpanel/bin/localpanel create \
  SITE_NAME IP_ADDRESS DOMAIN \
  --doc-root /var/www/SITE_NAME
```

The command validates site names and IPv4 addresses, writes the Apache
configuration, validates Apache before reloading it, and preserves the
document root when a virtual host is deleted.

## `config.php`

`config.php` contains installation-specific values, including:

- Debug mode
- MySQL administrator credentials used for site operations
- The main LocalPanel database connection
- The default web-root directory
- The local database management URL

Do not commit production credentials or share this file. The upcoming
installer will ask for these values during first-run setup.

## Apache permissions

The installer will determine the Apache service user and add a restricted
sudoers entry similar to:

```text
www-data ALL=(ALL) NOPASSWD: /var/www/localpanel/bin/localpanel
```

On Fedora-based distributions, the Apache user is commonly `apache` instead
of `www-data`. Always review the generated entry with:

```bash
sudo visudo -c
```

If the installer reports filesystem permission errors, ensure the Apache
user can read the application and write only to the directories that the
application is intended to manage.

## Accessing LocalPanel

Configure Apache to serve the project and open:

```text
http://localhost/localpanel
```

For a custom local hostname, add the corresponding address and domain to
`/etc/hosts`, for example:

```text
127.0.0.2 localpanel.local
```

Then access the site at `http://localpanel.local`.

## Current release status

The Python installer described above is planned for the upcoming release.
Until it is available, install the system dependencies, configure
`config.php`, add the restricted sudoers entry manually, and run
`create.sql` yourself.

### TO DO

- [ ] Add a Python installer script to automate the installation process.
- [ ] Add file manager and terminal features to the web interface.
- [ ] Custom right click menu to interact with sites in the list.
- [ ] Add a way to display untracked sites in the list.
- [ ] Add a search bar to filter the list of sites.
- [ ] Sync column data from filesystem if unknown
