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
use Lifyzer\Server\Core\Container\Provider\SwiftMailer;
use Psr\Container\ContainerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\ParameterBag;

class Product extends Base
{
    private const ADD_PRODUCT_VIEW_FILE = 'product/add.twig';
    private const SUBMIT_PRODUCT_VIEW_FILE = 'product/submit.twig';
    private const EMAIL_NEW_PRODUCT_VIEW_FILE = 'email/new-product-details.twig';
    private const EMAIL_SUBJECT = 'New Product to be moderated';
    private const HTML_CONTENT_TYPE = 'text/html';

    /** @var ProductModel */
    private $productModel;

    /** @var Swift_Mailer */
    private $mailer;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->mailer = $container->get(SwiftMailer::class);
        $this->productModel = new ProductModel($container);
    }

    public function add(): void
    {
        $this->view->display(
            self::ADD_PRODUCT_VIEW_FILE,
            [
                'siteUrl' => SITE_URL,
                'siteName' => SITE_NAME,
                'pageName' => 'Add a Product'
            ]
        );
    }

    public function submit(): void
    {
        $request = $this->httpRequest->request;

        if ($request->get('addproduct') && !$this->isSpamBot($request)) {
            $data = $request->all();

            // Remove unused param since we don't bind it
            unset($data['addproduct']);

            if (empty($data['barcode'])) {
                $data['barcode'] = '';
            }

            if ($this->isFormCompleted($data)) {
                $data['productId'] = $this->productModel->addToPending($data);
                $this->sendEmail($data);
            } else {
                header('Location: /');
                exit;
            }
        }

        $this->view->display(
            self::SUBMIT_PRODUCT_VIEW_FILE,
            [
                'siteUrl' => SITE_URL,
                'siteName' => SITE_NAME,
                'pageName' => 'Add a Product',
                'message' => 'Product successfully submitted'
            ]
        );
    }

    public function approve(array $data): void
    {
        if ($this->isSecurityHashValid($data)) {
            $productId = (int)$data['id'];
            $this->productModel->moveToLive($productId);
            echo 'Approved! :)';
        } else {
            echo 'An error occurred...';
        }
    }

    public function disapprove(array $data): void
    {
        if ($this->isSecurityHashValid($data)) {
            $productId = (int)$data['id'];
            $this->productModel->discard($productId);
            echo 'Product discard! :(';
        } else {
            echo 'An error occurred...';
        }
    }

    private function sendEmail(array $data): void
    {
        $adminEmail = getenv('ADMIN_EMAIL');

        $urls = [
            'approvalUrlHash' => $this->getApprovalUrl($data['productId']),
            'disapprovalUrlHash' => $this->getDisapprovalUrl($data['productId'])
        ];

        $message = (new Swift_Message(self::EMAIL_SUBJECT))
            ->setFrom($adminEmail)
            ->setTo($adminEmail)
            ->setBody(
                $this->view->render(
                    self::EMAIL_NEW_PRODUCT_VIEW_FILE,
                    array_merge($data, $urls)
                ),
                self::HTML_CONTENT_TYPE
            );

        $this->mailer->send($message);
    }

    private function getApprovalUrl(int $productId): string
    {
        return sprintf(
            '%sapprove/%s/%d',
            SITE_URL,
            getenv('SECURITY_HASH'),
            $productId
        );
    }

    private function getDisapprovalUrl(int $productId): string
    {
        return sprintf(
            '%sdisapprove/%s/%d',
            SITE_URL,
            getenv('SECURITY_HASH'),
            $productId
        );
    }

    /**
     * Make sure that a human fulfilled the form (a bot would fulfil "firstname" field as well).
     *
     * @param ParameterBag $request
     *
     * @return bool
     */
    private function isSpamBot(ParameterBag $request): bool
    {
        return (bool)$request->get('firstname');
    }

    private function isFormCompleted(array $fields): bool
    {
        foreach ($fields as $name => $value) {
            if (empty($name) || trim($value) === '') {
                return false;
            }

            return true;
        }
    }

    private function isSecurityHashValid(array $data): bool
    {
        return $data['hash'] === getenv('SECURITY_HASH');
    }
}
