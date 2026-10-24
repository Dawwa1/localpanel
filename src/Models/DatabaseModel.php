<?php

namespace Localpanel\Models;

use Localpanel\JsonResponse;
use React\Http\Message\Response;
use React\MySQL\ConnectionInterface;
use React\MySQL\Factory;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;
use function React\Async\await;
use function React\Promise\reject;

require_once __DIR__ . '/../../config.php';

class DatabaseModel
{
    private ConnectionInterface $db;
    private static DatabaseModel $instance;

    private function __construct()
    {
        $uri = sprintf(
            '%s:%s@%s/%s',
            rawurlencode(MAIN_DB['user']),
            rawurlencode(MAIN_DB['pass']),
            MAIN_DB['host'],
            MAIN_DB['dbname']
        );

        $this->db = (new Factory())->createLazyConnection($uri);
    }

    /**
     * Returns the database connection.
     *
     *
     * @return DatabaseModel The database connection.
     */
    public static function getInstance(): DatabaseModel
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Creates a new site in the database.
     *
     * @param string $name The name of the site.
     * @param string $ip The IP address of the site.
     * @param string $domain The domain name of the site.
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPass
     * @param bool $hidden Whether the site is hidden.
     * @param string|null $root_path The root path of the site.
     * @param string|null $conf_path The configuration path of the site.
     * @return Response The ID of the created site.
     */
    public function createSite(string $name, string $ip, string $domain, string $dbName,
                               string $dbUser, string $dbPass, bool $hidden = false,
                               ?string $root_path = null, ?string $conf_path = null): Response
    {
        $ex = await($this->exists('ip', $ip));
        if ($ex) {
            return JsonResponse::error('Site with this IP already exists', 400, 'SITE_ALREADY_EXISTS');
        }

        $tableQuery = 'INSERT INTO sites (name, ip, dom, hidden, root_path, conf_path) VALUES (?, ?, ?, ?, ?, ?)';
        $table = await($this->db->query($tableQuery, [$name, $ip, $domain, (int)$hidden, $root_path, $conf_path])
            ->then(static function (QueryResult $result): int {
                return $result->insertId;
            }));

        $userQuery = "CREATE USER '$dbUser'@'localhost' IDENTIFIED VIA mysql_native_password USING '$dbPass';
        GRANT USAGE ON *.* TO '$dbUser'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
        CREATE DATABASE IF NOT EXISTS `$dbName`;GRANT ALL PRIVILEGES ON `$dbUser`.* TO '$dbUser'@'localhost';";

        $queries = explode(';', $userQuery);
        try {
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    await($this->db->query($query));
                }
            }
        } catch (\Throwable $e) {
            return JsonResponse::error('Failed to create database user or grant privileges: ' . $e->getMessage(), 500, 'DB_USER_CREATION_FAILED');
        }

        if ($table) {
            return JsonResponse::success([
                'message' => 'Site created successfully',
                'site' => [
                    'name' => $name,
                    'ip' => $ip,
                    'domain' => $domain,
                    'hidden' => $hidden,
                    'root_path' => $root_path,
                    'conf_path' => $conf_path,
                ],
            ], 201);
        } else {
            return JsonResponse::error('Failed to create site', 500, 'SITE_CREATION_FAILED');
        }

    }

    public function getAllSites(): PromiseInterface
    {
        return $this->db
            ->query('SELECT * FROM sites')
            ->then(static function (QueryResult $result): array {
                return $result->resultRows;
            });
    }


    /**
     * Fetches a single site row using one of the supported lookup fields.
     *
     * Allowed identifiers are:
     * - name: site name
     * - ip: site IP address
     * - domain: site domain name
     *
     * Any other identifier is rejected with an InvalidArgumentException before the
     * query is executed.
     *
     * @param string $identifier The field name used in the WHERE clause.
     * @param mixed $value The value to match against the selected identifier.
     *
     * @return PromiseInterface Resolves to the matching site row or null.
     */
    public function getSite($identifier, $value): PromiseInterface
    {
        $allowedIdentifiers = ['name', 'ip', 'dom'];
        if (!in_array($identifier, $allowedIdentifiers, true)) {
            return reject(new \InvalidArgumentException('Invalid identifier'));
        }

        $query = "SELECT * FROM sites WHERE $identifier = '$value'";

        return $this->db
            ->query($query)
            ->then(static function (QueryResult $result): ?array {
                return $result->resultRows[0] ?? null;
            });
    }

    public function deleteSite(string $ip): PromiseInterface
    {
        return $this->db
            ->query('DELETE FROM sites WHERE ip = ?', [$ip])
            ->then(static function (QueryResult $result): bool {
                return $result->affectedRows > 0;
            });
    }

    public function countSites(): PromiseInterface
    {
        return $this->db
            ->query('SELECT COUNT(*) AS site_count FROM sites')
            ->then(static function (QueryResult $result): int {
                return (int) ($result->resultRows[0]['site_count'] ?? 0);
            });
    }

    public function exists(string $identifier, mixed $value): PromiseInterface
    {
        return $this->getSite($identifier, $value)
            ->then(static function (?array $siteData): bool {
                return $siteData !== null;
            });
    }

    public function query(string $sql): PromiseInterface
    {
        return $this->db->query($sql)->then(static function (QueryResult $result) {;
            return $result->resultRows;
        });
    }
}