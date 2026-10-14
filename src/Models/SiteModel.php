<?php

namespace Localpanel\Models;

use InvalidArgumentException;
use Localpanel\Models\DatabaseModel;
use React\Promise\PromiseInterface;

class SiteModel{
// database representation of site

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
     * @param bool $hidden Whether the site is hidden.
     * @param string $root_path The root path of the site.
     * @param string $conf_path The configuration path of the site.
     *
     * @return PromiseInterface The created site object.
     */
    public static function createSite(string $name, string $ip, string $domain, bool $hidden, string $root_path, string $conf_path): PromiseInterface {
        $database = DatabaseModel::getInstance();

        $ip_regex = '/^127\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';

        if ((!preg_match($ip_regex, $ip)) || (!filter_var($domain, FILTER_VALIDATE_DOMAIN))) {
            throw new InvalidArgumentException('Invalid IP address or domain');
        }

        if (empty($name) || empty($domain)) {
            throw new InvalidArgumentException('Name and domain are required');
        }

        return $database->createSite($name, $ip, $domain, $hidden, $root_path, $conf_path)
            ->then(static function (array $siteData) {
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