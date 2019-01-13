<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Controller;

use Lifyzer\Server\App\Model\Product as ProductModel;
use Lifyzer\Server\Core\Container\Provider\Monolog;
use Lifyzer\Server\Core\Container\Provider\SwiftMailer;
use PDOException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use stdClass;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\ParameterBag;

class Product extends Base
{
    private const INDEX_PRODUCT_VIEW_FILE = 'product/homepage.twig';
    private const SHOW_PRODUCT_VIEW_FILE = 'product/show.twig';

    /** @var ProductModel */
    private $productModel;

    /** @var Swift_Mailer */
    private $mailer;

    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->mailer = $container->get(SwiftMailer::class);
        $this->productModel = new ProductModel($container);
        $this->container = $container;
    }

    public function homepage(): void
    {
        $this->view->display(
            self::INDEX_PRODUCT_VIEW_FILE,
            [
                'siteUrl' => SITE_URL,
                'siteName' => SITE_NAME,
                'pageName' => 'Add a Product'
            ]
        );
    }

    public function show(int $productId): void
    {
        $productData = $this->productModel->get($productId);

        if (!empty($productData) && $productData instanceof stdClass) {
            $this->view->display(
                self::SHOW_PRODUCT_VIEW_FILE,
                [
                    'siteUrl' => SITE_URL,
                    'siteName' => SITE_NAME,
                    'pageName' => $productData->product_name,
                    'item' => $productData
                ]
            );
        } else {
            $this->redirectToHomepage();
        }
    }

    public function show(int $productId): void
    {
        $productData = $this->productModel->get($productId);

        if (!empty($productData)) {
            $this->view->display(
                self::SHOW_PRODUCT_VIEW_FILE,
                [
                    'siteUrl' => SITE_URL,
                    'siteName' => SITE_NAME,
                    'pageName' => 'Add a Product',
                    'item' => $productData
                ]
            );
        } else {
            $this->redirectToHomepage();
        }
    }

    private function redirectToHomepage(): void
    {
        header('Location: ' . SITE_URL);
        exit;
    }
}
