#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__ . '/../config.php';

const HOSTS_FILE = '/etc/hosts';
const DEFAULT_WEB_ROOT = WEB_DIR_PATH;

function usage(string $name): void
{
    fwrite(STDERR, <<<USAGE
Usage:
  {$name} create <site-name> <ipv4> <domain> [--doc-root <path>] | Creates a new virtual host and adds an entry to /etc/hosts.
  {$name} update <site-name> <ipv4> <domain> [--doc-root <path>] | Updates an existing virtual host and updates the entry in /etc/hosts.
  {$name} delete <site-name> [--full-delete] | Deletes an existing virtual host and removes the entry from /etc/hosts. The document root is preserved by default, but can be deleted with the --full-delete option.
  {$name} restart | Restarts the Apache service.

USAGE);
}

/**
 * Recursively removes a directory and its contents.
 * From: https://stackoverflow.com/a/3338133
 *
 * @param string $dir The directory path to remove.
 */
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                    rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                else
                    unlink($dir. DIRECTORY_SEPARATOR .$object);
            }
        }
        rmdir($dir);
    }
}

function fail(string $message): never
{
    fwrite(STDERR, "Error: {$message}\n");
    exit(1);
}

function isValidIPv4(string $ip): bool
{
    $parts = explode('.', $ip);
    if (count($parts) !== 4) {
        return false;
    }

    foreach ($parts as $part) {
        if ($part === '' || !ctype_digit($part) || (int) $part < 0 || (int) $part > 255) {
            return false;
        }
    }

    return true;
}

function isValidDomain(string $domain): bool
{
    return preg_match(
        '/^[A-Za-z0-9]([A-Za-z0-9-]{0,61}[A-Za-z0-9])?(\.[A-Za-z0-9]([A-Za-z0-9-]{0,61}[A-Za-z0-9])?)+$/',
        $domain
    ) === 1;
}

function validateSiteName(string $siteName): void
{
    if (preg_match('/^[A-Za-z0-9._-]+$/', $siteName) !== 1) {
        fail("Invalid site name '{$siteName}'. Allowed characters: letters, numbers, dot, underscore, dash.");
    }
}

function validateValues(string $siteName, string $ip, string $domain): void
{
    validateSiteName($siteName);

    if (!isValidIPv4($ip)) {
        fail("Invalid IPv4 address '{$ip}'.");
    }

    // Kept for parity with the original command's domain validator.
    isValidDomain($domain);
}

/**
 * @return array{layout: string, path: string, service: string}
 */
function detectApache(?string $siteName): array
{
    if (is_dir('/etc/apache2/sites-available')) {
        return [
            'layout' => 'debian',
            'path' => "/etc/apache2/sites-available/{$siteName}.conf",
            'root' => WEB_DIR_PATH,
            'service' => 'apache2',
        ];
    }

    if (is_dir('/etc/httpd/conf.d')) {
        return [
            'layout' => 'fedora',
            'path' => "/etc/httpd/conf.d/{$siteName}.conf",
            'root' => WEB_DIR_PATH,
            'service' => 'httpd',
        ];
    }

    fail('Could not detect Apache config layout (expected /etc/apache2 or /etc/httpd).');
}

/**
 * @return array{ip: string, domain: string, docRoot: string}
 */
function readExistingVhost(string $path): array
{
    if (!is_file($path)) {
        fail("Virtual host configuration not found: {$path}");
    }

    $contents = file_get_contents($path);
    if ($contents === false) {
        fail("Could not read virtual host configuration: {$path}");
    }

    $ip = '';
    $domain = '';
    $docRoot = '';

    if (preg_match('/^<VirtualHost ([^:\s]+):80>.*$/m', $contents, $matches) === 1) {
        $ip = $matches[1];
    }
    if (preg_match('/^\s*ServerName\s+(\S+).*$/m', $contents, $matches) === 1) {
        $domain = $matches[1];
    }
    if (preg_match('/^\s*DocumentRoot\s+(.+)$/m', $contents, $matches) === 1) {
        $docRoot = trim($matches[1]);
    }

    if ($ip === '' || $domain === '') {
        fail("Could not read the IP and domain from: {$path}");
    }

    return ['ip' => $ip, 'domain' => $domain, 'docRoot' => $docRoot];
}

function removeHostsEntry(string $ip, string $domain): void
{
    $contents = file_get_contents(HOSTS_FILE);
    if ($contents === false) {
        fail('Could not read ' . HOSTS_FILE . '.');
    }

    $lines = preg_split('/\R/', $contents);
    if ($lines === false) {
        fail('Could not parse ' . HOSTS_FILE . '.');
    }

    $kept = [];
    foreach ($lines as $line) {
        $fields = preg_split('/\s+/', trim($line));
        $remove = false;

        if ($fields !== false && count($fields) > 0 && $fields[0] === $ip) {
            $remove = in_array($domain, $fields, true);
        }

        if (!$remove) {
            $kept[] = $line;
        }
    }

    if (file_put_contents(HOSTS_FILE, implode("\n", $kept)) === false) {
        fail('Could not write ' . HOSTS_FILE . '.');
    }
}

function setHostsEntry(string $ip, string $domain): void
{
    removeHostsEntry($ip, $domain);

    $handle = fopen(HOSTS_FILE, 'ab');
    if ($handle === false) {
        fail('Could not open ' . HOSTS_FILE . ' for writing.');
    }

    if (fwrite($handle, "{$ip}\t{$domain}\n") === false) {
        fclose($handle);
        fail('Could not write ' . HOSTS_FILE . '.');
    }
    fclose($handle);
}

function findExecutable(string ...$names): ?string
{
    $pathValue = getenv('PATH') ?: '';
    foreach (explode(PATH_SEPARATOR, $pathValue) as $directory) {
        foreach ($names as $name) {
            $path = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
            if (is_file($path) && is_executable($path)) {
                return $path;
            }
        }
    }

    return null;
}

/**
 * @param list<string> $arguments
 */
function runCommand(string $executable, array $arguments, bool $quiet = false): void
{
    $command = array_merge([$executable], $arguments);
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => $quiet ? ['file', '/dev/null', 'w'] : ['pipe', 'w'],
        2 => $quiet ? ['file', '/dev/null', 'w'] : ['pipe', 'w'],
    ];

    $process = proc_open($command, $descriptors, $pipes);
    if (!is_resource($process)) {
        fail("Could not execute {$executable}.");
    }

    if (isset($pipes[0]) && is_resource($pipes[0])) {
        fclose($pipes[0]);
    }
    if (!$quiet) {
        foreach ([1, 2] as $index) {
            if (isset($pipes[$index]) && is_resource($pipes[$index])) {
                stream_copy_to_stream($pipes[$index], $index === 1 ? STDOUT : STDERR);
                fclose($pipes[$index]);
            }
        }
    }

    $status = proc_close($process);
    if ($status !== 0) {
        fail("Command failed: {$executable} " . implode(' ', $arguments));
    }
}

/**
 * @param list<string> $arguments
 */
function runQuiet(string $executable, array $arguments): int
{
    $process = proc_open(
        array_merge([$executable], $arguments),
        [
            0 => ['pipe', 'r'],
            1 => ['file', '/dev/null', 'w'],
            2 => ['file', '/dev/null', 'w'],
        ],
        $pipes
    );
    if (!is_resource($process)) {
        return 1;
    }

    fclose($pipes[0]);
    return proc_close($process);
}

function validateApache(): void
{
    $executable = findExecutable('apachectl', 'apache2ctl', 'httpd');
    if ($executable === null) {
        fail('Could not find an Apache configuration test command.');
    }

    runCommand($executable, ['-t']);
}

function reloadApache(string $service): void
{
    $systemctl = findExecutable('systemctl');
    if ($systemctl !== null) {
        $command = [$systemctl, 'reload', $service];
        $process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes);

        if (is_resource($process)) {
            fclose($pipes[0]);
            stream_copy_to_stream($pipes[1], STDOUT);
            stream_copy_to_stream($pipes[2], STDERR);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $status = proc_close($process);
            if ($status === 0) {
                return;
            }
        }

        runCommand($systemctl, ['restart', $service]);
        return;
    }

    $serviceCommand = findExecutable('service');
    if ($serviceCommand === null) {
        fail('Could not find systemctl or service.');
    }
    runCommand($serviceCommand, [$service, 'restart']);
}

function enableVhost(array $config, string $siteName): void
{
    if ($config['layout'] !== 'debian') {
        return;
    }

    $executable = findExecutable('a2ensite');
    if ($executable !== null) {
        if (runQuiet($executable, ["{$siteName}.conf"]) !== 0) {
            fail("Command failed: {$executable} {$siteName}.conf");
        }
    }
}

function disableVhost(array $config, string $siteName): void
{
    if ($config['layout'] !== 'debian') {
        return;
    }

    $executable = findExecutable('a2dissite');
    if ($executable !== null) {
        runQuiet($executable, ["{$siteName}.conf"]);
    }
}

function writeVhost(array $config, string $siteName, string $ip, string $domain, string $docRoot): void
{
    if (!is_dir($docRoot) && !mkdir($docRoot, 0755, true) && !is_dir($docRoot)) {
        fail("Could not create document root: {$docRoot}");
    }

    $contents = <<<VHOST
<VirtualHost {$ip}:80>
    ServerName {$domain}
    DocumentRoot {$docRoot}

    LogLevel info
    ErrorLog {$docRoot}/error.log
    CustomLog {$docRoot}/access.log combined
</VirtualHost>

VHOST;

    if (file_put_contents($config['path'], $contents) === false) {
        fail("Could not write Apache configuration: {$config['path']}");
    }
}

function createVhost(array $config, string $siteName, string $ip, string $domain, string $docRoot): void
{
    validateValues($siteName, $ip, $domain);
    writeVhost($config, $siteName, $ip, $domain, $docRoot);
    setHostsEntry($ip, $domain);
    enableVhost($config, $siteName);
    validateApache();
    reloadApache($config['service']);

    echo "Virtual host created: {$siteName}\n";
    echo "Configuration: {$config['path']}\n";
    echo "Hosts entry set: {$ip} {$domain}\n";
}

function updateVhost(array $config, string $siteName, string $ip, string $domain, string $docRoot): void
{
    validateValues($siteName, $ip, $domain);
    $existing = readExistingVhost($config['path']);
    writeVhost($config, $siteName, $ip, $domain, $docRoot);
    removeHostsEntry($existing['ip'], $existing['domain']);
    setHostsEntry($ip, $domain);
    validateApache();
    reloadApache($config['service']);

    echo "Virtual host updated: {$siteName}\n";
    echo "Configuration: {$config['path']}\n";
    echo "Hosts entry set: {$ip} {$domain}\n";
}

function deleteVhost(array $config, string $siteName, bool $fullDelete = false): void
{

    validateSiteName($siteName);
    $existing = readExistingVhost($config['path']);
    disableVhost($config, $siteName);

    if (!unlink($config['path'])) {
        fail("Could not delete Apache configuration: {$config['path']}");
    }
    removeHostsEntry($existing['ip'], $existing['domain']);
    validateApache();
    reloadApache($config['service']);

    if ($fullDelete) {
        $docRoot = $existing['docRoot'] !== '' ? $existing['docRoot'] : DEFAULT_WEB_ROOT . '/' . $siteName;
        try {
            rrmdir($docRoot);
        } catch (Exception $e) {
            fail("Could not delete document root: {$docRoot}");
        }
        echo "Document root deleted: {$docRoot}\n";
    } else{
        echo "Document root preserved: {$docRoot}\n";
    }

    echo "Virtual host deleted: {$siteName}\n";
    echo "Deleted host entry in /etc/hosts\n";
}

function parseDocRoot(array $arguments, int $offset): string
{
    if (count($arguments) === $offset) {
        return DEFAULT_WEB_ROOT . '/' . $arguments[1];
    }
    if (count($arguments) !== $offset + 2 || !in_array($arguments[$offset], ['--doc-root', '-d'], true)) {
        usage(basename($_SERVER['argv'][0]));
        exit(1);
    }

    if ($arguments[$offset + 1] === '') {
        fail('Document root cannot be empty.');
    }

    return $arguments[$offset + 1];
}

if (php_sapi_name() === 'cli') {

    if (function_exists('posix_geteuid') && posix_geteuid() !== 0) {
        fail('This script must be run as root.');
    }

    $arguments = array_slice($_SERVER['argv'], 1);
    $scriptName = basename($_SERVER['argv'][0]);
    if (count($arguments) < 1) {
        usage($scriptName);
        exit(1);
    }

    $action = $arguments[0];
    if (in_array($action, ['create', 'update'], true)) {
        if (count($arguments) < 4) {
            usage($scriptName);
            exit(1);
        }

        $siteName = $arguments[1];
        $ip = $arguments[2];
        $domain = $arguments[3];
        $docRoot = parseDocRoot($arguments, 4);
    } elseif ($action === 'delete') {
        $fullDelete = false;
        if (count($arguments) >= 3 && str_starts_with($arguments[2], '--full-delete')) {
            $val = explode('=', $arguments[2], 2)[1] ?? '';
            $fullDelete = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        }

        if (count($arguments) < 2 || (count($arguments) > 2 && !str_starts_with($arguments[2], '--full-delete'))) {
            usage($scriptName);
            exit(1);
        }

        $siteName = $arguments[1];
        $docRoot = '';
    }

    $config = @detectApache($siteName);
    switch ($action) {
        case 'create':
            createVhost($config, $siteName, $ip, $domain, $docRoot);
            break;
        case 'update':
            updateVhost($config, $siteName, $ip, $domain, $docRoot);
            break;
        case 'delete':
            deleteVhost($config, $siteName, $fullDelete);
            break;
        case 'restart':
            reloadApache($config['service']);
            echo "Apache service restarted: {$config['service']}\n";
            break;
        default:
            usage($scriptName);
            exit(1);
    }
}