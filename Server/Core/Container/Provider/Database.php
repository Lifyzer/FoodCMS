<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Server\Core\Container\Provider;

use PDO;

class Database implements Providable
{
    private const DBMS_MYSQL_NAME = 'MySQL';
    private const DBMS_POSTGRESQL_NAME = 'PostgreSQL';
    private const DSN_MYSQL_PREFIX = 'mysql';
    private const DSN_POSTGRESQL_PREFIX = 'pgsql';
    private const DBMS_CHARSET = 'UTF8';

    public function getService(): PDO
    {
        static $instance;

        if ($instance === null) {
            $instance = $this->createPdoInstance();
        }

        return $instance;
    }

    private function createPdoInstance(): PDO
    {
        $details = $this->getDetails();

        $driverOptions[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$details['charset']}";
        $pdo = new PDO("{$details['db_type']}:host={$details['host']};dbname={$details['name']};", $details['user'], $details['password'], $driverOptions);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    private function getDetails(): array
    {
        return [
            'db_type' => self::DSN_MYSQL_PREFIX,
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PWD'),
            'charset' => self::DBMS_CHARSET
        ];
    }
}
