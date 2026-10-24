<?php

namespace Localpanel\Models;

use InvalidArgumentException;
use React\Promise\PromiseInterface;
use React\Stream\ThroughStream;
use Throwable;

class SiteModel{

    public string $name;
    public string $ip;
    public string $domain;
    public bool $hidden;
    public string $root_path;
    public string $conf_path;
    public \DateTime $created_at;

    private function __construct(string $name, string $ip, string $domain, bool $hidden,
                                 string $root_path, string $conf_path, string $created_at){
        $this->name = $name;
        $this->ip = $ip;
        $this->domain = $domain;
        $this->hidden = $hidden;
        $this->root_path = $root_path;
        $this->conf_path = $conf_path;
        $this->created_at = date_create($created_at);

    }

    /**
     * Fetches a single site using the provided identifier.
     *
     * @param string $identifier The field name used in the WHERE clause.
     * @param mixed $value The value to match against the specified identifier.
     *
     * @return PromiseInterface The site object, or null if not found.
     */
    public static function getSite(string $identifier, mixed $value): PromiseInterface
    {
        $database = DatabaseModel::getInstance();

        return $database->getSite($identifier, $value)
            ->then(static function (?array $siteData) {
                if ($siteData === null) {
                    throw new \InvalidArgumentException('Invalid identifier or site not found');
                }

                return new self(
                    $siteData['name'] ?? '',
                    $siteData['ip'] ?? '',
                    $siteData['dom'] ?? '',
                    (bool)$siteData['hidden'] ?? false,
                    $siteData['root_path'] ?? '',
                    $siteData['conf_path'] ?? '',
                    $siteData['created_at'] ?? ''
                );
            });
    }

    public static function getAllSites(): PromiseInterface
    {
        $database = DatabaseModel::getInstance();

        return $database->getAllSites()
            ->then(static function (array $sitesData) {
                $sites = [];
                foreach ($sitesData as $siteData) {
                    $sites[] = new self(
                        $siteData['name'] ?? '',
                        $siteData['ip'] ?? '',
                        $siteData['dom'] ?? '',
                        (bool)$siteData['hidden'] ?? false,
                        $siteData['root_path'] ?? '',
                        $siteData['conf_path'] ?? '',
                        $siteData['created_at'] ?? ''
                    );
                }

                return $sites;
            });
    }

    /**
     * Creates a new site.
     *
     * @param string $name The name of the site.
     * @param string $ip The IP address of the site.
     * @param string $domain The domain name of the site.
     * @param array|null $dbData
     * @return array|ThroughStream The created site object.
     * @throws Throwable
     */
    public static function createSite(string $name, string $ip, string $domain, ?array $dbData = null): array|\React\Stream\ThroughStream
    {
        $ip_regex = '/^127\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';

        if ((!preg_match($ip_regex, $ip)) || (!filter_var($domain, FILTER_VALIDATE_DOMAIN))) {
            throw new InvalidArgumentException('Invalid IP address or domain');
        }

        if (empty($name) || empty($domain)) {
            throw new InvalidArgumentException('Name and domain are required');
        }

        if (isset($dbData)) {
            $database = DatabaseModel::getInstance();

            $dbName = $dbData['db_name'];
            $dbUser = $dbData['db_user'];
            $dbPass = $dbData['db_pass'];

            if (is_dir('/etc/apache2/sites-available')) {
                $confPath = '/etc/apache2/sites-available/' . $name . '.conf';
            } else {
                $confPath = '/etc/httpd/conf.d/' . $name . '.conf';
            }

            $dbStatus = $database->createSite($name, $ip, $domain, $dbName, $dbUser, $dbPass, false, WEB_DIR_PATH . $name, $confPath);

            if (!$dbStatus) {
                throw new InvalidArgumentException('Failed to create site in the database');
            }
        }

        return RunModel::createCmd($name, $ip, $domain);
    }

    public static function deleteSite(string $ip): PromiseInterface
    {
        $database = DatabaseModel::getInstance();

        return $database->deleteSite($ip)
            ->then(static function (bool $deleted) use ($ip) {
                if (!$deleted) {
                    throw new InvalidArgumentException('Failed to delete site from the database');
                }

                return RunModel::deleteCmd($ip);
            });
    }

    public function toJson(): string{
        return json_encode([
            'name' => $this->name,
            'ip' => $this->ip,
            'domain' => $this->domain,
            'hidden' => $this->hidden,
            'root_path' => $this->root_path,
            'conf_path' => $this->conf_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ], JSON_PRETTY_PRINT);
    }

}