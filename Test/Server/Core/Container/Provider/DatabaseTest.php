<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace Lifyzer\Test\Server\Core\Container\Provider;

use Lifyzer\Server\Core\Container\Provider\Database;
use Lifyzer\Server\Core\Container\Provider\Providable;
use PDO;
use Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testGetService(): void
    {
        /** @var Providable|Phake_IMock $database */
        $database = Phake::mock(Database::class);
        $this->assertInstanceOf(PDO::class, $database->getService());
    }
}
