<?php

declare(strict_types=1);

namespace Lifyzer\Test\Server\App\Model;

use Lifyzer\Server\App\Model\Product;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/** @requires extension pdo_sqlite */
class ProductTest extends TestCase
{
    private function model(): Product
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec('CREATE TABLE product (id INTEGER PRIMARY KEY, product_name TEXT)');
        $db->exec("INSERT INTO product VALUES (1, 'Apple')");
        $container = new class($db) implements ContainerInterface {
            private $db;
            public function __construct(PDO $db) { $this->db = $db; }
            public function get($id) { return $this->db; }
            public function has($id): bool { return true; }
        };
        return new Product($container);
    }

    public function testExistingProductRetainsItsData(): void
    {
        $product = $this->model()->get(1);
        self::assertSame('Apple', $product->product_name);
    }

    public function testMissingProductCanBeHandledByTheController(): void
    {
        self::assertNull($this->model()->get(999));
    }
}
