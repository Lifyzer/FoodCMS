<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

declare(strict_types=1);

namespace Lifyzer\Server\App\Controller;

use Lifyzer\Server\App\Model\Product as ProductModel;
use Lifyzer\Server\Core\Container\Provider\SwiftMailer;
use Psr\Container\ContainerInterface;
use stdClass;
use Swift_Mailer;

class Product extends Base
{
    private const INDEX_PRODUCT_VIEW_FILE = 'homepage.twig';
    private const SHOW_PRODUCT_VIEW_FILE = 'product/show.twig';
    private const SEARCH_PRODUCT_VIEW_FILE = 'product/search.twig';
    private const RESULTS_PRODUCT_VIEW_FILE = 'product/results.twig';

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
        $this->view->display(self::INDEX_PRODUCT_VIEW_FILE);
    }

    public function show(array $data): void
    {
        $productData = $this->productModel->get($data['productId']);

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

    public function search(): void
    {
        $keywords = $this->httpRequest->request->get('keywords');
        if ($keywords && strlen($keywords) > 0) {
            $this->redirectKeywordsToResults($keywords);
        }

        $this->view->display(
            self::SEARCH_PRODUCT_VIEW_FILE,
            [
                'siteUrl' => SITE_URL,
                'siteName' => SITE_NAME,
                'pageName' => 'Search a product',
            ]
        );
    }

    public function result(array $data): void
    {
        $items = $this->productModel->search($data['keywords']);
        if (!empty($items) && is_array($items)) {
            $this->view->display(
                self::RESULTS_PRODUCT_VIEW_FILE,
                [
                    'siteUrl' => SITE_URL,
                    'siteName' => SITE_NAME,
                    'pageName' => 'Foodstuffs Results',
                    'items' => $items
                ]
            );
        } else {
            $this->view->display(
                self::INDEX_PRODUCT_VIEW_FILE,
                [
                    'error_msg' => 'Item not found'
                ]
            );
        }
    }

    private function redirectToHomepage(): void
    {
        header('Location: ' . SITE_URL);
        exit;
    }

    private function redirectKeywordsToResults(string $keywords): void
    {
        $url = SITE_URL . 'results/' . $keywords;
        header('Location: ' . $url);
        exit;
    }
}
