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
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Pagerfanta\Pagerfanta;
use Psr\Container\ContainerInterface;
use stdClass;
use Swift_Mailer;

class Product extends Base
{
    private const ITEMS_PER_PAGE = 20;

    private const NEARBY_PAGES_LIMIT = 4;
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
        $productId = (int)$data['id'];
        $productData = $this->productModel->get($productId);

        if (!empty($productData) && $productData instanceof stdClass) {
            $this->view->display(
                self::SHOW_PRODUCT_VIEW_FILE,
                [
                    'siteUrl' => SITE_URL,
                    'siteName' => SITE_NAME,
                    'pageName' => $productData->product_name,
                    'item' => $productData,
                    'itemPlaceholder' => SITE_URL . 'static/img/product/noimage.svg'
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
        $keywords = $data['keywords'];

        try {
            $adapter = new ArrayAdapter(
                $this->productModel->search($keywords)
            );
            $pagerfanta = new Pagerfanta($adapter);
            $pagerfanta->setMaxPerPage(self::ITEMS_PER_PAGE);

            if (isset($data['page'])) {
                $pagerfanta->setCurrentPage($data['page']);
            }

            $offset = $pagerfanta->getCurrentPageOffsetStart();
            $limit = $pagerfanta->getMaxPerPage();
            $items = $this->productModel->search($keywords, $offset, $limit);

            if (!empty($items) && is_array($items)) {
                $this->view->display(
                    self::RESULTS_PRODUCT_VIEW_FILE,
                    [
                        'siteUrl' => SITE_URL,
                        'siteName' => SITE_NAME,
                        'pageName' => 'Foodstuffs Results',
                        'keywords' => $keywords,
                        'items' => $items,
                        'nearbyPagesLimit' => self::NEARBY_PAGES_LIMIT,
                        'currentPage' => $pagerfanta->getCurrentPage(),
                        'totalPages' => $pagerfanta->getNbPages()
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
        } catch (OutOfRangeCurrentPageException $except) {
            (new Error($this->container))->notFound();
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
