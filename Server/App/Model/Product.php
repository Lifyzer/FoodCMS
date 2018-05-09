<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Model;

use PDO;
use Psr\Container\ContainerInterface;

class Product
{
    private const QUERY_ADD_PRODUCT_TO_PENDING = '
      INSERT INTO pending_product (barcode_id, product_name, ingrediants, sugar, carbohydrate, saturated_fats, dietary_fiber, protein, salt, sodium, alcohol, product_image, is_organic, is_healthy) 
      VALUES(:barcode, :name, :ingredients, :sugar, :carbohydrate, :saturatedfat, :fiber, :protein, :salt, :sodium, :alcohol, :image, :isorganic, :ishealthy)';

    private const QUERY_MOVE_PRODUCT_TO_LIVE = '
      INSERT INTO product (barcode_id, product_name, ingrediants, sugar, carbohydrate, saturated_fats, dietary_fiber, protein, salt, sodium, alcohol, product_image, is_organic, is_healthy) 
      SELECT barcode_id, product_name, ingrediants, sugar, carbohydrate, saturated_fats, dietary_fiber, protein, salt, sodium, alcohol, product_image, is_organic, is_healthy FROM pending_product WHERE id = :productId LIMIT 1';

    private const QUERY_DELETE_PRODUCT_FROM_PENDING = 'DELETE FROM pending_product  WHERE id = :productId LIMIT 1';


    /** @var PDO */
    private $db;

    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get(Database::class);
    }

    /**
     * @param array $binds
     *
     * @return int Returns the product's ID
     */
    public function addToPending(array $binds): int
    {
        $stmt = $this->db->prepare(self::QUERY_ADD_PRODUCT_TO_PENDING);
        $stmt->execute($binds);

        return (int)$this->db->lastInsertId();
    }

    public function moveToLive(int $productId): bool
    {
        $stmt = $this->db->prepare(self::QUERY_MOVE_PRODUCT_TO_LIVE);
        $stmt->bindValue('productId', $productId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function discard(int $productId): bool
    {
        $stmt = $this->db->prepare(self::QUERY_DELETE_PRODUCT_FROM_PENDING);
        $stmt->bindValue('productId', $productId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
