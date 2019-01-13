<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Model;

use Lifyzer\Server\Core\Container\Provider\Database;
use PDO;
use Psr\Container\ContainerInterface;
use stdClass;

class Product
{
    private const QUERY_GET_PRODUCT = 'SELECT * FROM product WHERE id = :productId LIMIT 1';
    private const QUERY_SEARCH_PRODUCT = 'SELECT * FROM product WHERE product_name LIKE :keywords';

    /** @var PDO */
    private $db;

    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get(Database::class);
    }

    public function get(int $productId): stdClass
    {
        $stmt = $this->db->prepare(self::QUERY_GET_PRODUCT);
        $stmt->bindValue('productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchObject();
    }

    public function search(string $keywords): array
    {
        $stmt = $this->db->prepare(self::QUERY_SEARCH_PRODUCT);
        $stmt->bindValue('keywords', '%' . $keywords . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
