<?php

namespace Localpanel\Models;

use React\MySQL\ConnectionInterface;
use React\MySQL\Factory;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;
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
     * @param bool $hidden Whether the site is hidden.
     * @param string $root_path The root path of the site.
     * @param string $conf_path The configuration path of the site.
     *
     * @return PromiseInterface The ID of the created site.
     */
    public function createSite(string $name, string $ip, string $domain, bool $hidden, string $root_path, string $conf_path): PromiseInterface
    {
        $query = 'INSERT INTO sites (name, ip, dom, hidden, root_path, conf_path) VALUES (?, ?, ?, ?, ?, ?)';
        return $this->db->query($query, [$name, $ip, $domain, (int)$hidden, $root_path, $conf_path])
            ->then(static function (QueryResult $result): int {
                return (int)$result->insertId;
            });
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

    public function countSites(): PromiseInterface
    {
        return $this->db
            ->query('SELECT COUNT(*) AS site_count FROM sites')
            ->then(static function (QueryResult $result): int {
                return (int) ($result->resultRows[0]['site_count'] ?? 0);
            });
    }

    public function query(string $sql): PromiseInterface
    {
        return $this->db->query($sql)->then(static function (QueryResult $result) {;
            return $result->resultRows;
        });
    }
}